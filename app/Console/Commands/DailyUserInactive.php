<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailToUserInactive;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DailyUserInactive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:user_inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        $role_user = app('config')->get('auth.auth');
        $yesterday = Date::now()->addDays(-1)->toDate();

        $users = User::whereHas('role', function ($q) use ($role_user) {
            $q->where('name', $role_user['role_user']);
        })  ->where('last_active_datetime', '<', $yesterday)
            ->orWhereNull('last_active_datetime')
            ->get();

        if (!empty($users)) {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new MailToUserInactive($user));
            }
        }
    }
}
