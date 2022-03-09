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
     * Check user read post
     *
     * @param $id
     * @return void
     */
    public function check_user_read_post($id)
    {
        // Update time user view post
        $now = Date::now()->toDate();
        Post::where('id', $id)->update(['last_view_datetime' => $now]);

        // Check auth read post
        if (Auth::check()) {
            $userRead = Read_Posts::where('user_id', Auth::id())->where('post_id', $id)->first();
            if (is_null($userRead)) {
                $user = User::find(Auth::id());
                $user->post()->attach($id);
                $userRead = Read_Posts::where('user_id', Auth::id())->where('post_id', $id)->first();
            }
            $userRead->times = $userRead->times + 1;
            $userRead->save();
        }
    }
}
