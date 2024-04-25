<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToProgramSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programme_schedule', function (Blueprint $table) {
            $table->bigInteger("from_number")->nullable()->after("to_string");
            $table->bigInteger("to_number")->nullable()->after("from_number");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('programme_schedule', function (Blueprint $table) {
            $table->dropColumn("from_number");
            $table->dropColumn("to_number");
        });
    }
}
