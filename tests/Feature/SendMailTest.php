<?php
use App\Actions\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


it('sends an email successfully', function () {

    Mail::fake();

    $mail_data = [
        'email' => 'recipient@example.com',
        'subject' => 'Test Subject',
        'view' => 'emails.test',
        'receiver_name' => 'John Doe',
        'link' => 'https://example.com/reset-password',
    ];

    (new SendMail)->send_mail($mail_data);

    Mail::assertNothingSent();
});

it('logs an error if mail sending fails', function () {

    Mail::fake();
    Log::spy();

    $mail_data = [
        'email' => 'invalid_email',
        'subject' => 'Test Subject',
        'view' => 'emails.test',
    ];

    Mail::shouldReceive('send')
        ->andThrow(new Exception('Mail send error'));

    (new SendMail)->send_mail($mail_data);

    Log::shouldHaveReceived('error')->withArgs(function ($message) {
        return str_contains($message, 'Mail send error');
    });
});

