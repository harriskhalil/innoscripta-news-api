<?php
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\SendMail;

uses(RefreshDatabase::class);


beforeEach(function () {
    $this->sendMailMock = Mockery::mock(SendMail::class);
    app()->instance(SendMail::class, $this->sendMailMock);
});

it('sends a password reset token to the user', function () {
    Mail::fake();

    $user = User::factory()->create([
        'email' => 'test@example.com',
        'name' => 'John Doe',
    ]);

    $this->sendMailMock->shouldReceive('send_mail')->once()->with(Mockery::on(function ($mail_data) use ($user) {
        return $mail_data['email'] === $user->email;
    }));

    $response = $this->postJson('/api/auth/password/reset', [
        'email' => 'test@example.com',
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Password verification link has sent to your email. Please open your mailbox and verify it. Thanks']);

    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => 'test@example.com',
    ]);
});

it('fails to send a reset token if the email does not exist', function () {
    $response = $this->postJson('/api/auth/password/reset', [
        'email' => 'invalid@example.com',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

