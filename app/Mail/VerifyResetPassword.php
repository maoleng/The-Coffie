<?php

namespace App\Mail;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class VerifyResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build(): VerifyResetPassword
    {
        return $this
            ->from('napoleon_dai_de@tanthe.com', env('APP_NAME'))
            ->subject(getConfig('reset_password_title'))
            ->view('mail.reset_password', [
                'code' => $this->code,
            ]);
    }
}
