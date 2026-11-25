<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login\Login;
use App\Http\Resources\Auth\SessionResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function login(Login $request): JsonResponse
    {
        $credentials = $request->validated();
        $session = $this->authService->login($credentials['login'], $credentials['password']);

        if ($session === null) {
            return ResponseHelper::error(
                ['en' => 'Invalid login credentials.', 'ar' => 'بيانات تسجيل الدخول غير صحيحة.'],
                (object) ['code' => 'INVALID_CREDENTIALS'],
                401
            );
        }

        return ResponseHelper::success(
            SessionResource::make($session['user'])->resolve($request) + [
                'token' => $session['token'],
                'expires_at' => $session['expires_at'],
            ],
            ['en' => 'Signed in successfully.', 'ar' => 'تم تسجيل الدخول بنجاح.']
        )->header('Cache-Control', 'no-store');
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return ResponseHelper::success(null, ['en' => 'Signed out successfully.', 'ar' => 'تم تسجيل الخروج بنجاح.']);
    }

    public function refresh(Request $request): JsonResponse
    {
        return ResponseHelper::success(
            SessionResource::make($request->user()),
            ['en' => 'The session is valid.', 'ar' => 'الجلسة صالحة.']
        )->header('Cache-Control', 'no-store');
    }
}
