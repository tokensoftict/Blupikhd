<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStreamingUrlToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string("streamning_url")->nullable()->after("access_mode");
            //$table->enum("google_play_status",["ON","OFF"])->default("OFF")->after("access_mode");
            $table->enum("apple_play_status",["ON","OFF"])->default("OFF")->after("google_play_status");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn("streamning_url");
            //$table->dropColumn("google_play_status");
            $table->dropColumn("apple_play_status");
        });
    }
}
