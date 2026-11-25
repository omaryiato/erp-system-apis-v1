<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);

    Schema::create('users_v1', function (Blueprint $table): void {
        $table->id();
        foreach (['full_name', 'user_name', 'phone_number', 'email_address', 'password', 'user_type'] as $column) {
            $table->string($column)->nullable();
        }
        $table->integer('status')->default(1);
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->timestamps();
    });

    (require database_path('migrations/2026_08_24_155025_create_personal_access_tokens_table.php'))->up();
});

test('protected API requests return JSON 401 without requesting JSON', function () {
    foreach ([[], ['Accept' => 'text/html']] as $headers) {
        $this->get('/api/refresh', $headers)
            ->assertUnauthorized()
            ->assertJsonPath('errors.code', 'UNAUTHENTICATED');
    }
});

function createSecurityTestUser(array $attributes = []): User
{
    return User::create($attributes + [
        'full_name' => 'Security Test User',
        'user_name' => 'security-admin',
        'phone_number' => '0790000000',
        'password' => Hash::make('test-password'),
        'user_type' => 'admin',
        'status' => 1,
    ]);
}

test('all business API routes require authentication and an active admin', function () {
    foreach (app('router')->getRoutes() as $route) {
        if (! str_starts_with($route->uri(), 'api/') || $route->uri() === 'api/login') {
            continue;
        }

        expect($route->gatherMiddleware())->toContain('auth:sanctum', 'admin.access', 'throttle:erp-api');
    }

    $this->get('/api/refresh')->assertUnauthorized();
    $user = createSecurityTestUser(['user_type' => 'owner']);
    $token = $user->createToken('legacy')->plainTextToken;
    $this->withToken($token)->getJson('/api/refresh')->assertForbidden();

    Auth::forgetGuards();
    $user->update(['user_type' => 'admin', 'status' => 0]);
    $this->withToken($token)->getJson('/api/refresh')->assertForbidden();
});

test('admin login rotates the previous token and logout revokes the current token', function () {
    $user = createSecurityTestUser();
    $previous = $user->createToken('previous')->plainTextToken;

    $response = $this->postJson('/api/login', ['login' => $user->phone_number, 'password' => 'test-password'])
        ->assertOk()
        ->assertJsonPath('data.status', 1)
        ->assertJsonMissingPath('data.password');

    $token = $response->json('data.token');
    expect($user->tokens()->count())->toBe(1);
    expect($user->tokens()->sole()->expires_at->isFuture())->toBeTrue();

    $this->withToken($previous)->getJson('/api/refresh')->assertUnauthorized();

    Auth::forgetGuards();
    $this->withToken($token)->getJson('/api/refresh')->assertOk()->assertJsonMissingPath('data.token');
    Auth::forgetGuards();
    $this->withToken($token)->postJson('/api/logout')->assertOk();
    Auth::forgetGuards();
    $this->withToken($token)->getJson('/api/refresh')->assertUnauthorized();
});

test('invalid admin logins are limited with a bilingual retry response', function () {
    createSecurityTestUser(['user_type' => 'manager']);

    for ($attempt = 0; $attempt < 10; $attempt++) {
        $this->postJson('/api/login', ['login' => 'security-admin', 'password' => 'test-password'])
            ->assertUnauthorized();
    }

    $this->postJson('/api/login', ['login' => 'SECURITY-ADMIN', 'password' => 'test-password'])
        ->assertTooManyRequests()
        ->assertHeader('Retry-After')
        ->assertJsonStructure(['message' => ['ar', 'en'], 'errors' => ['code']]);
});

test('legacy tokens expire and no destructive GET route is registered', function () {
    $user = createSecurityTestUser();
    $token = $user->createToken('legacy');
    $token->accessToken->forceFill(['created_at' => now()->subHours(3)])->save();

    $this->withToken($token->plainTextToken)->getJson('/api/refresh')->assertUnauthorized();
    $this->getJson('/api/hr/truncate')->assertNotFound();
});

test('account security changes revoke existing tokens', function () {
    $user = createSecurityTestUser();
    $user->createToken('previous');

    app(\App\Services\UserService::class)->updateUser([
        'full_name' => $user->full_name,
        'user_name' => $user->user_name,
        'phone_number' => $user->phone_number,
        'password' => 'new-test-password',
        'status' => 0,
        'user_type' => 'admin',
    ], $user, $user->id);

    expect($user->tokens()->count())->toBe(0);
});

test('authenticated requests are limited per user', function () {
    $user = createSecurityTestUser();
    $token = $user->createToken('test')->plainTextToken;

    for ($attempt = 0; $attempt < 120; $attempt++) {
        $this->withToken($token)->getJson('/api/refresh')->assertOk();
    }

    $this->withToken($token)->getJson('/api/refresh')
        ->assertTooManyRequests()
        ->assertHeader('Retry-After');
});

test('audit fields on users come from the authenticated actor', function () {
    $creator = createSecurityTestUser();
    $editor = createSecurityTestUser(['user_name' => 'security-editor', 'phone_number' => '0790000001']);
    $payload = [
        'full_name' => 'Created Through API',
        'user_name' => 'audit-subject',
        'phone_number' => '0790000002',
        'password' => 'test-password',
        'user_type' => 'employee',
        'status' => 1,
        'created_by' => $editor->id,
        'updated_by' => $creator->id,
    ];

    $this->postJson('/api/user', $payload)->assertUnauthorized();

    $this->withToken($creator->createToken('creator')->plainTextToken)
        ->postJson('/api/user', $payload)->assertCreated();
    $subject = User::query()->where('user_name', 'audit-subject')->firstOrFail();
    expect($subject->created_by)->toBe($creator->id);
    expect($subject->updated_by)->toBeNull();

    Auth::forgetGuards();
    $payload['created_by'] = $editor->id;
    $this->withToken($editor->createToken('editor')->plainTextToken)
        ->putJson('/api/user/'.$subject->id, $payload)->assertSuccessful();

    $subject->refresh();
    expect($subject->created_by)->toBe($creator->id);
    expect($subject->updated_by)->toBe($editor->id);
});
