<?php
namespace App\Actions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;
class SendMail
{
    use AsAction;
    public function send_mail($mail_data)
    {
        try {
            Mail::send($mail_data['view'], $mail_data, function( $message ) use ($mail_data)
            {
                $message->to($mail_data['email'])->subject($mail_data['subject']);
            });

            if (count(Mail::failures()) > 0) {

                Log::error('Failed to send mail', Mail::failures());

            }

        }
        catch(\Exception $e)
        {
            Log::error('Mail send error: ' . $e->getMessage());
        }
    }
}
