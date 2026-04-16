<?php

namespace App\Http\Controllers;

use App\Services\PublicApiCatalog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;

class PublicApiSettingsController extends Controller
{
    public function index(Request $request)
    {
        return view('user.settings', [
            'user' => $request->user(),
            'publicApiGroups' => PublicApiCatalog::groups(),
            'publicApiRoutes' => PublicApiCatalog::routeRows(),
            'publicApiBaseUrl' => url('/api/public'),
            'publicApiTokens' => $request->user()->tokens()
                ->where('is_public_api', true)
                ->latest()
                ->get(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $request->user()->update([
            'public_api_enabled' => $request->boolean('public_api_enabled'),
        ]);

        return redirect()
            ->route('profile')
            ->with('success', __('ui.public_api.messages.settings_saved'));
    }

    public function storeToken(Request $request)
    {
        $validated = $this->validateTokenPayload($request);
        $abilities = $this->normalizeAbilities($validated['abilities']);

        $newToken = $request->user()->createToken($validated['name'], $abilities);
        $newToken->accessToken->forceFill([
            'is_public_api' => true,
            'is_enabled' => $request->boolean('is_enabled', true),
        ])->save();

        return redirect()
            ->route('profile')
            ->with('success', __('ui.public_api.messages.token_created'))
            ->with('public_api_created_token', $newToken->plainTextToken)
            ->with('public_api_created_token_name', $validated['name']);
    }

    public function updateToken(Request $request, int $token)
    {
        $tokenModel = $this->findOwnedPublicToken($request, $token);
        $validated = $this->validateTokenPayload($request);

        $tokenModel->forceFill([
            'name' => $validated['name'],
            'abilities' => $this->normalizeAbilities($validated['abilities']),
            'is_enabled' => $request->boolean('is_enabled'),
        ])->save();

        return redirect()
            ->route('profile')
            ->with('success', __('ui.public_api.messages.token_updated'));
    }

    public function destroyToken(Request $request, int $token)
    {
        $this->findOwnedPublicToken($request, $token)->delete();

        return redirect()
            ->route('profile')
            ->with('success', __('ui.public_api.messages.token_deleted'));
    }

    private function validateTokenPayload(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'abilities' => ['required', 'array', 'min:1'],
            'abilities.*' => ['string', Rule::in(PublicApiCatalog::keys())],
        ]);
    }

    private function normalizeAbilities(array $abilities): array
    {
        return array_values(array_unique($abilities));
    }

    private function findOwnedPublicToken(Request $request, int $tokenId): PersonalAccessToken
    {
        return $request->user()->tokens()
            ->where('is_public_api', true)
            ->findOrFail($tokenId);
    }
}
