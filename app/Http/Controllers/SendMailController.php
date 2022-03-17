<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailToUser;
use App\Models\SendMail;
use Exception;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Config;

class SendMailController extends Controller
{

    private SendMail $sendMail;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->sendMail = new SendMail();
    }

    public function index()
    {
        $status = Config::get('appication.send_mail.status');
        $this->sendMail->set_up_sendmail($status['pending']);
        $mails = $this->sendMail->mail_user();
        foreach ($mails as $mail) {
            try {
                $this->sendMail->change_status($mail->id, $status['sending']);

                $send_mail = new SendMailToUser($mail->user->email, $mail->title);
                Queue::push($send_mail);

                $this->sendMail->change_status($mail->id, $status['done']);

            } catch (Exception $e) {
                $this->sendMail->change_status($mail->id, $status['error'], $e->getMessage());
            }
        }

        return redirect(route('screen_user_home'));
    }
}
