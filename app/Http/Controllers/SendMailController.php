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
        $this->sendMail = new SendMail();
        $status = Config::get('appication.send_mail.status');
        $this->sendMail->setUpSendMail($status['pending']);
        $mails = $this->sendMail->mailUser();

        foreach ($mails as $mail) {
            $send_mail = new SendMailToUser($mail->user->email, $mail->title, $mail->id);
            Queue::push($send_mail);
        }

        return redirect(route('screen_user_home'));
    }
}
