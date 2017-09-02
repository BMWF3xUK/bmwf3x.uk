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

    public function user()
    {
        return $this->belongsTo(User::class, "id", "id");
    }
}
