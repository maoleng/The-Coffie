<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class VerifyNewLocation extends Mailable
{
    use Queueable, SerializesModels;

    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build(): VerifyNewLocation
    {
        return $this
            ->from('napoleon_dai_de@tanthe.com', env('APP_NAME'))
            ->subject(getConfig('verify_new_location_title'))
            ->view('mail.verify_new_location', [
                'code' => $this->code,
            ]);
    }
}
