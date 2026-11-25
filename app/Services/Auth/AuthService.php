<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Timebox;

class AuthService
{
    public function __construct(private readonly UserRepository $users) {}

    /**
     * @return array{user: User, token: string, expires_at: \DateTimeInterface}|null
     */
    public function login(string $login, string $password): ?array
    {
        return (new Timebox)->call(function (Timebox $timebox) use ($login, $password): ?array {
            $session = DB::transaction(function () use ($login, $password): ?array {
                $user = $this->users->findForLogin($login);

                if ($user === null || (int) $user->status !== 1 || $user->user_type !== 'admin'
                    || ! Hash::check($password, $user->password)) {
                    return null;
                }

                $this->users->revokeTokens($user);
                $token = $this->users->issueToken($user);

                return [
                    'user' => $user,
                    'token' => $token->plainTextToken,
                    'expires_at' => $token->accessToken->expires_at,
                ];
            });

            if ($session !== null) {
                $timebox->returnEarly();
            }

            return $session;
        }, 300_000);
    }

    public function logout(User $user): void
    {
        $this->users->revokeCurrentToken($user);
    }
}
