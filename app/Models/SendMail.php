<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class SendMail extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'send_mail';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'status',
        'user_id',
        'message'
    ];

    /**
     * Relation with user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get data sendmail with user
     *
     * @return Builder[]|Collection
     */
    public function mailUser()
    {
        $status = Config::get('appication.send_mail.status');
        return SendMail::with('user')->where('status', $status['pending'])->get();
    }

    /**
     * Setup user send mail with status pending
     *
     * @throws Exception
     */
    public function setUpSendMail($status)
    {
        DB::beginTransaction();
        try {
//            DB::table('send_mail')->truncate();
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                $data['title'] = 'Notification to ' . $user->name;
                $data['status'] = $status;
                $data['user_id'] = $user->id;
                DB::table('send_mail')->insert($data);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Change status sendmail
     *
     * @param $id
     * @param $status
     * @param $message
     * @return void
     */
    public function changeStatus($id, $status, $message = null)
    {
        $data['status'] = $status;
        if ($message) {
            $data['message'] = $message;
        }
        Db::table('send_mail')->where('id', $id)->update($data);
    }
}
