<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        DB::setDefaultConnection('sqlite');
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::connection('sqlite')->dropIfExists('devices');
        Schema::connection('sqlite')->dropIfExists('personal_access_tokens');
        Schema::connection('sqlite')->dropIfExists('users');

        Schema::connection('sqlite')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->longText('image');
            $table->string('role')->default('user');
            $table->boolean('public_api_enabled')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_public_api')->default(false);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->string('url');
            $table->string('name_board')->nullable();
            $table->text('command')->nullable();
            $table->boolean('available')->default(true);
            $table->boolean('ota')->default(false);
            $table->boolean('configuration')->default(false);
            $table->timestamps();
        });
    }

    public function test_public_api_is_rejected_when_disabled_for_account(): void
    {
        $user = User::factory()->create([
            'public_api_enabled' => false,
        ]);

        $token = $this->createToken($user, ['public-api.profile.read'], true, true);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/public/me')
            ->assertForbidden()
            ->assertJson([
                'message' => 'Public API is disabled for this account.',
            ]);
    }

    public function test_non_public_tokens_cannot_access_public_api(): void
    {
        $user = User::factory()->create([
            'public_api_enabled' => true,
        ]);

        $token = $this->createToken($user, ['public-api.profile.read'], false, true);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/public/me')
            ->assertForbidden()
            ->assertJson([
                'message' => 'This token cannot be used with the public API.',
            ]);
    }

    public function test_enabled_public_token_can_read_profile(): void
    {
        $user = User::factory()->create([
            'public_api_enabled' => true,
        ]);

        $token = $this->createToken($user, ['public-api.profile.read'], true, true);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/public/me')
            ->assertOk()
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonPath('data.public_api_enabled', true);
    }

    public function test_public_api_abilities_are_enforced(): void
    {
        $user = User::factory()->create([
            'public_api_enabled' => true,
        ]);

        $token = $this->createToken($user, ['public-api.profile.read'], true, true);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/public/devices')
            ->assertForbidden()
            ->assertJson([
                'message' => 'This token does not have access to the requested endpoint.',
                'required_ability' => 'public-api.devices.read',
            ]);
    }

    public function test_settings_page_creates_public_api_token(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('settings.public-api.tokens.store'), [
                'name' => 'Desktop integration',
                'abilities' => ['public-api.profile.read', 'public-api.devices.read'],
                'is_enabled' => 1,
            ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('public_api_created_token');

        $token = PersonalAccessToken::query()
            ->where('tokenable_id', $user->id)
            ->where('name', 'Desktop integration')
            ->first();

        $this->assertNotNull($token);
        $this->assertTrue((bool) $token->is_public_api);
        $this->assertTrue((bool) $token->is_enabled);
        $this->assertSame(['public-api.profile.read', 'public-api.devices.read'], $token->abilities);
    }

    public function test_settings_page_is_rendered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile'))
            ->assertOk()
            ->assertSee(__('ui.public_api.page_title'))
            ->assertSee(__('ui.public_api.create_token_title'));
    }

    private function createToken(User $user, array $abilities, bool $isPublicApi, bool $isEnabled): string
    {
        $newToken = $user->createToken('Test token', $abilities);
        $newToken->accessToken->forceFill([
            'is_public_api' => $isPublicApi,
            'is_enabled' => $isEnabled,
        ])->save();

        return $newToken->plainTextToken;
    }
}
