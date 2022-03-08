<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        return $this->from('dhv0612@gmail.com')
            ->view('mail.mail-to-admin-post-unread')
            ->with('admin', $this->admin)
            ->with('posts', $this->posts);
    }
}
