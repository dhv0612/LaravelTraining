<?php

namespace App\Jobs;

use App\Mail\MailTo3rdPartyIntegration;
use App\Models\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Exception;

class SendMailToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $title;
    protected $id;
    private $sendMail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $title, $id)
    {
        $this->email = $email;
        $this->title = $title;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendMail = new SendMail();
        $status = Config::get('appication.send_mail.status');
        $this->sendMail->changeStatus($this->id, $status['sending']);

        try{
            Mail::to($this->email)->send(new MailTo3rdPartyIntegration($this->title));
            $this->sendMail->changeStatus($this->id, $status['done']);
        } catch (Exception $e){
            $this->sendMail->changeStatus($this->id, $status['error'], $e->getMessage());
        }
    }
}
