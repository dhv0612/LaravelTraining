<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'last_active_datetime'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relation with role
     *
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relation with post
     *
     * @return BelongsToMany
     */
    public function post()
    {
        return $this->belongsToMany(Post::class, 'read_posts', 'user_id', 'post_id')->withTimestamps();
    }

    /**
     * Function update last view time of post
     *
     * @param $id
     * @return void
     */
    public function updateLastViewTime($id)
    {
        $now = Date::now()->toDate();
        Post::where('id', $id)->update(['last_view_datetime' => $now]);
    }

    /**
     * Check user read post
     *
     * @param $id
     * @return void
     */
    public function checkUserReadPost($id)
    {
        if (Auth::check()) {
            $user_read = Read_Posts::where('user_id', Auth::id())->where('post_id', $id)->first();
            if (is_null($user_read)) {
                $user = User::find(Auth::id());
                $user->post()->attach($id);
                $user_read = Read_Posts::where('user_id', Auth::id())->where('post_id', $id)->first();
            }
            $user_read->times = $user_read->times + 1;
            $user_read->save();
        }
    }

    /**
     * Function get voucher
     *
     * @param $id
     * @return void
     */
    public function getVoucherToMe($id)
    {
        $post = Post::find($id);
        $check_count_voucher = Read_Posts::lockForUpdate()->where('post_id', $id)->where('get_voucher', '1')->count();

        if (Auth::check() &&
            $post->voucher_enabled &&
            $check_count_voucher < $post->voucher_quantity) {

            $check_voucher_user = Read_Posts::where('user_id', Auth::id())->where('post_id', $id)->first();

            if (is_null($check_voucher_user)) {
                $user = User::find(Auth::id());
                $user->post()->attach($id);
                $check_voucher_user = Read_Posts::where('user_id', Auth::id())->where('post_id', $id)->first();
            }

            if (!$check_voucher_user->get_voucher) {
                $check_voucher_user->get_voucher = true;
                $check_voucher_user->save();
            }
        }
    }
}
