<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        "id",
        "name",
        "administrator",
    ];

    protected $casts = [
        "id" => "integer",
        "name" => "string",
        "administrator" => "boolean",
    ];

    protected $with = [
        "groups",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "id", "id");
    }

    public function groups()
    {
        return $this->belongsToMany(FacebookGroup::class)
                    ->using(FacebookGroupMember::class);
    }
}
