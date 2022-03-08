<?php

namespace App\Console\Commands;

use App\Mail\MailToAdminPostUnread;
use App\Mail\MailToUserInactive;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DailyPostUnread extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:post_unread';

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
    public function handle(Request $request)
    {
        $host = $request->getSchemeAndHttpHost();
        $role_user = app('config')->get('auth.auth');

        $admins = User::whereHas('role', function ($q) use ($role_user) {
            $q->where('name', $role_user['role_admin']);
        })->get();

        $today = Date::today()->toDate();
        $posts = Post:: where('last_view_datetime', '<', $today)
                        ->orWhereNull('last_view_datetime')->get();

        if (!empty($admins) && !empty($posts)) {
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new MailToAdminPostUnread($admin, $posts));
            }
        }
    }
}
