<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiCredentials
{
    public function handle(Request $request, Closure $next)
    {
        $appId = $request->header('X-App-ID');
        $appSecret = $request->header('X-App-Secret');

        if ($appId !== config('services.api.app_id') || $appSecret !== config('services.api.app_secret')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
