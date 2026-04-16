<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePublicApiAbility
{
    public function handle(Request $request, Closure $next, string $ability)
    {
        $user = $request->user();

        if (!$user || !$user->tokenCan($ability)) {
            return response()->json([
                'message' => 'This token does not have access to the requested endpoint.',
                'required_ability' => $ability,
            ], 403);
        }

        return $next($request);
    }
}
