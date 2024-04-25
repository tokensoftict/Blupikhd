<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProgrammeImageToProgrammeScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programme_schedule', function (Blueprint $table) {
            $table->string('programme_image')->nullable()->after('to_number');
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
            $table->dropColumn('programme_image');
        });
    }
}
