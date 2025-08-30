<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_route_valid_credentials(): void
    {
        $name = fake()->name();
        $user = User::factory()->create([
            'name' => $name,
        ]);

        $this->postJson('login', [
            'name' => $name,
            'password' => 'password',
        ])->assertNoContent();
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_route_invalid_credentials(): void
    {
        $this->postJson('login', [
            'name' => fake()->word(),
            'password' => 'password',
        ])->assertUnauthorized();
        $this->assertFalse($this->isAuthenticated());
    }

    public function test_logout_route(): void
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson('logout')
            ->assertSuccessful();
        $this->assertFalse($this->isAuthenticated('web'));
    }
}
