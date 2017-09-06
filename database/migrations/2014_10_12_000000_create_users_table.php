<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("users", function (Blueprint $table) {
            $table->bigIncrements("id")->unsigned();
            $table->string("token")->index()->nullable();
            $table->unsignedInteger("token_expires_in")->index()->default(0);
            $table->dateTime("token_expires_at")->index()->nullable();
            $table->string("nickname")->index()->nullable();
            $table->string("name")->index();
            $table->string("email")->index()->nullable();
            $table->string("avatar")->index();
            $table->string("avatar_original")->index();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("users");
    }
}
