<?php

use App\FacebookGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebookGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("facebook_groups", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("name");
            $table->timestamps();
        });

        FacebookGroup::create([
            "id" => "1067824023310213",
            "name" => "BMW F3x UK",
        ]);

        FacebookGroup::create([
            "id" => "137629026920263",
            "name" => "BMW F3x Worldwide",
        ]);

        FacebookGroup::create([
            "id" => "750176628505991",
            "name" => "BMW F2x Worldwide",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("facebook_groups");
    }
}
