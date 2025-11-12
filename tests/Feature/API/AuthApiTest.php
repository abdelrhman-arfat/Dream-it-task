<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_register_user_successfully()
    {
        $payload = [
            'name_ar' => 'أحمد',
            'name_en' => 'Ahmed',
            'email' => 'ahmed@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'ahmed@example.com',
        ]);
    }

    public function test_register_user_fails_with_missing_fields()
    {
        $payload = [];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJsonStructure(['success', 'message']);
    }

    public function test_register_user_fails_with_invalid_email()
    {
        $payload = [
            'name_ar' => 'أحمد',
            'name_en' => 'Ahmed',
            'email' => 'invalid-email',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJsonStructure(['success', 'message']);
    }

    public function test_register_user_fails_when_passwords_do_not_match()
    {
        $payload = [
            'name_ar' => 'أحمد',
            'name_en' => 'Ahmed',
            'email' => 'ahmed@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret321',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJsonStructure(['success', 'message']);
    }

    public function test_login_user_successfully()
    {
        $user = User::factory()->create([
            'email' => 'ahmed@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $payload = [
            'email' => 'ahmed@example.com',
            'password' => 'secret123',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure();
    }

    public function test_login_fails_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'ahmed@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $payload = [
            'email' => 'ahmed@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => __('messages.auth.failed')
            ]);
    }

    public function test_login_fails_with_non_existing_email()
    {
        $payload = [
            'email' => 'notexists@example.com',
            'password' => 'secret123',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(422)
        ->assertJsonStructure(['success', 'message']);
    }

    public function test_login_fails_with_missing_fields()
    {
        $payload = [];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(422)
            ->assertJsonStructure(['success', 'message']);
    }
}
