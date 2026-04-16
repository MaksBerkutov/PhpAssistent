<?php

namespace App\Http\Controllers\PublicApi;

use Illuminate\Http\Request;

class ProfileController extends ApiController
{
    public function show(Request $request)
    {
        $user = $this->currentUser($request);
        $token = $user->currentAccessToken();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'email_verified_at' => optional($user->email_verified_at)?->toIso8601String(),
            'public_api_enabled' => (bool) $user->public_api_enabled,
            'created_at' => optional($user->created_at)?->toIso8601String(),
            'updated_at' => optional($user->updated_at)?->toIso8601String(),
            'current_token' => [
                'name' => $token?->name,
                'abilities' => $token?->abilities ?? [],
                'last_used_at' => optional($token?->last_used_at)?->toIso8601String(),
            ],
        ]);
    }
}
