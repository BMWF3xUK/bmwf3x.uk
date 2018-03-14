<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookGroupMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("facebook_group_member", function (Blueprint $table) {
            $table->unsignedBigInteger("facebook_group_id");
            $table->unsignedBigInteger("member_id");

            $table->primary([
                "facebook_group_id",
                "member_id",
            ], "facebook_group_member_index");

            $table
                ->foreign("facebook_group_id")
                ->references("id")
                ->on("facebook_groups")
                ->onDelete("cascade");

            $table
                ->foreign("member_id")
                ->references("id")
                ->on("members")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("facebook_group_member");
    }
}
