<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccessibility
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return ResponseHelper::error(
                ['en' => 'Authentication required.', 'ar' => 'المصادقة مطلوبة.'],
                (object) ['code' => 'UNAUTHENTICATED'],
                401
            );
        }

        if ((int) $user->status !== 1 || $user->user_type !== 'admin') {
            return ResponseHelper::error(
                ['en' => 'Access denied.', 'ar' => 'غير مصرح بالوصول.'],
                (object) ['code' => 'FORBIDDEN'],
                403
            );
        }

        return $next($request);
    }
}
