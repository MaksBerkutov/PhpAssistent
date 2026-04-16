<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePublicApiAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $token = $user?->currentAccessToken();

        if (!$user || !$token) {
            return response()->json([
                'message' => 'Public API requires a bearer token.',
            ], 401);
        }

        if (!(bool) $user->public_api_enabled) {
            return response()->json([
                'message' => 'Public API is disabled for this account.',
            ], 403);
        }

        if (!(bool) $token->is_public_api) {
            return response()->json([
                'message' => 'This token cannot be used with the public API.',
            ], 403);
        }

        if (!(bool) $token->is_enabled) {
            return response()->json([
                'message' => 'This public API token is disabled.',
            ], 403);
        }

        return $next($request);
    }
}
