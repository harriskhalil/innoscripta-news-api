<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a user to register with valid data', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->post('/api/auth/register', $data);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'token',
            ]
        ]);

    expect(User::where('email', 'testuser@example.com')->exists())->toBeTrue();

    $user = User::where('email', 'testuser@example.com')->first();
    expect(Hash::check('password', $user->password))->toBeTrue();
});

it('fails registration with invalid data', function () {
    $data = [
        'name' => 'Test User',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->postJson('/api/auth/register', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('allows a user to log in with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'testuser@example.com',
        'password' => Hash::make('password'),
    ]);

    $data = [
        'email' => 'testuser@example.com',
        'password' => 'password',
    ];

    $response = $this->post('/api/auth/login', $data);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'token',
            ]
        ]);


    expect(auth()->user()->id)->toBe($user->id);
});

it('fails login with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'testuser@example.com',
        'password' => Hash::make('password'),
    ]);

    $data = [
        'email' => 'testuser@example.com',
        'password' => 'wrongpassword',
    ];

    $response = $this->postJson('/api/auth/login', $data);

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid Credentials',
        ]);
});

it('logs out the user successfully', function () {

    $user = User::factory()->create();
    \Laravel\Sanctum\Sanctum::actingAs($user, ['*']);

    $response = $this->postJson('/api/auth/logout');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'User Logged out Successfully',
        ]);

    expect(\Illuminate\Support\Facades\DB::table('personal_access_tokens')
        ->where('tokenable_id', $user->id)
        ->exists())
        ->toBeFalse();
});



