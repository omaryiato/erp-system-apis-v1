<?php

namespace App\Providers;

use App\Helpers\ResponseHelper;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $tooManyRequests = static fn (Request $request, array $headers): JsonResponse => ResponseHelper::error(
            ['en' => 'Too many requests. Try again later.', 'ar' => 'طلبات كثيرة. حاول مرة أخرى لاحقاً.'],
            (object) ['code' => 'RATE_LIMIT_EXCEEDED'],
            429
        )->withHeaders($headers);

        RateLimiter::for('erp-login', static function (Request $request) use ($tooManyRequests): array {
            $login = $request->input('login');
            $identity = hash('sha256', is_string($login) ? Str::lower(trim($login)) : '');

            return [
                Limit::perMinute(30)->by('ip:'.$request->ip())->response($tooManyRequests),
                Limit::perMinute(10)->by('login:'.$identity)->response($tooManyRequests),
            ];
        });

        RateLimiter::for('erp-api', static fn (Request $request): Limit => Limit::perMinute(120)
            ->by('user:'.($request->user()?->getAuthIdentifier() ?? 'ip:'.$request->ip()))
            ->response($tooManyRequests));
    }
}
