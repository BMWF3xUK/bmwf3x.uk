<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id",
        "token",
        "token_expires_in",
        "token_expires_at",
        "nickname",
        "name",
        "email",
        "avatar",
        "avatar_original",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "remember_token",
    ];

    protected $casts = [
        "id" => "integer",
        "token" => "string",
        "token_expires_in" => "integer",
        "token_expires_at" => "dateTime",
        "nickname" => "string",
        "name" => "string",
        "email" => "string",
        "avatar" => "string",
        "avatar_original" => "string",
    ];

    protected $with = [
        "member",
    ];

    public static function selectOrCreateFromFacebookUser($fb_user)
    {
        $user = self::firstOrNew([
            "id" => $fb_user->id,
        ]);

        $user->id = $fb_user->id;
        $user->token = $fb_user->token;
        $user->token_expires_in = $fb_user->expiresIn;
        $user->token_expires_at = Carbon::now(config("app.timezone"))->addSeconds($fb_user->expiresIn);
        $user->nickname = $fb_user->nickname;
        $user->name = $fb_user->name;
        $user->email = $fb_user->email;
        $user->avatar = $fb_user->avatar;
        $user->avatar_original = $fb_user->avatar_original;
        $user->save();

        return $user;
    }

    public function member()
    {
        return $this->hasOne(Member::class, "id", "id");
    }

    public function getIsMemberAttribute()
    {
        return (bool)$this->member;
    }
}
