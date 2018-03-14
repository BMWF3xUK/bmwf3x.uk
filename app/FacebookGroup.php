<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookGroup extends Model
{
    protected $fillable = [
        "id",
        "name",
    ];

    protected $casts = [
        "id" => "integer",
        "name" => "string",
    ];

    public function members()
    {
        return $this->belongsToMany(Member::class)
                    ->using(FacebookGroupMember::class);
    }
}
