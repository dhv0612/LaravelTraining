<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class MailTo3rdPartyIntegration extends Mailable
{
    use Queueable, SerializesModels;

    protected $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from_user = Config::get('mail.from.address');
        return $this->from($from_user)
            ->view('mail.mail-to-user-3rd-party-integration')
            ->with('title', $this->title);
    }
}
