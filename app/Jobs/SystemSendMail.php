<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SystemSendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $email;
    private mixed $mail_content;
    private string $type;

    public function __construct($data)
    {
        $this->email = $data['email'];
        $this->mail_content = $data['mail_content'];
        $domain = explode('@', $data['email'])[1];
        $this->type = $domain === 'student.tdtu.edu.vn' ? 'school' : 'normal';
    }

    public function handle(): void
    {
        Mail::mailer($this->type)->to($this->email)->send($this->mail_content);
    }
}
