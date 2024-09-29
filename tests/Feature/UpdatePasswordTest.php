<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);



it('updates the user password with a valid token', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('old_password'),
    ]);

    $token = Hash::make('test@example.com' . \Illuminate\Support\Str::random(20));

    \App\Models\PasswordReset::create([
        'email' => 'test@example.com',
        'token' => $token,
        'created_at' => \Carbon\Carbon::now(),
    ]);

    $response = $this->put('/api/auth/password/update', [
        'token' => $token,
        'password' => 'new_password',
        'password_confirmation' => 'new_password',
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Logged In']);

    $this->assertTrue(\Illuminate\Support\Facades\Auth::attempt(['email' => 'test@example.com', 'password' => 'new_password']));
});

it('fails to update password with an invalid or expired token', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $token = Hash::make('invalidtoken');

    $response = $this->put('/api/auth/password/update', [
        'token' => $token,
        'password' => 'new_password',
        'password_confirmation' => 'new_password',
    ]);

    $response->assertStatus(422)
        ->assertJson(['message' => 'Invalid password reset request.Please Try again.']);
});
