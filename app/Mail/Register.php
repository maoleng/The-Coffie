<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class Register extends Mailable
{
    use Queueable, SerializesModels;

    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build(): Register
    {
        return $this
            ->from('napoleon_dai_de@tanthe.com', env('APP_NAME'))
            ->subject(getConfig('register_title'))
            ->view('mail.register', [
                'code' => $this->code,
            ]);
    }
}
