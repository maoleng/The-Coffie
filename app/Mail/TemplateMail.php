<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class TemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    private mixed $title;
    private mixed $content;
    private mixed $sender;

    public function __construct($template)
    {
        $this->title = $template->title;
        $this->content = $template->content;
        $this->sender = $template->sender;
    }

    public function build(): TemplateMail
    {
        return $this
            ->from('napoleon_dai_de@tanthe.com', $this->sender)
            ->subject($this->title)
            ->html($this->content);
    }
}
