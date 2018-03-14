<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FacebookGroupMember extends Pivot
{
    public $incrementing = false;

    protected $table = "facebook_group_member";

    public $timestamps = false;
}
