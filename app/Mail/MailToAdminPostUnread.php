<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class MailToAdminPostUnread extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $posts;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admin, $posts)
    {
        $this->admin = $admin;
        $this->posts = $posts;
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
            ->view('mail.mail-to-admin-post-unread')
            ->with('admin', $this->admin)
            ->with('posts', $this->posts);
    }
}
