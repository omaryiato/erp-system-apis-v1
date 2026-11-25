<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DataValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($request->except(['password', 'password_confirmation', 'current_password']) as $key => $value) {
            if (! is_string($value)) {
                continue;
            }

            if (preg_match('/<script\b[^>]*>(.*?)<\/script>|<iframe|<embed|<object/is', $value)) {
                return ResponseHelper::error(
                    [
                        'en' => trans('validation.invalid_data', [], 'en'),
                        'ar' => trans('validation.invalid_data', [], 'ar'),
                    ],
                    (object) ['field' => $key],
                    400
                );
            }
        }

        $response = $next($request);

        // Legacy controllers can return a server error without throwing.
        if ($response->getStatusCode() === 500) {
            return ResponseHelper::error(
                ['en' => 'An internal error occurred.', 'ar' => 'حدث خطأ داخلي.'],
                (object) ['code' => 'INTERNAL_ERROR'],
                500
            );
        }

        return $response;
    }
}
