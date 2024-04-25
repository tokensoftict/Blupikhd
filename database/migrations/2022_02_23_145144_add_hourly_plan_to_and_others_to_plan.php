<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHourlyPlanToAndOthersToPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan', function (Blueprint $table) {
            DB::statement("ALTER TABLE plan CHANGE COLUMN type `type` ENUM('HOURLY','DAILY', 'WEEKLY', 'MONTHLY','YEARLY') NOT NULL DEFAULT 'HOURLY'");
            $table->boolean('show_homepage')->after('status')->default(0);
            $table->integer('no_of_type')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan', function (Blueprint $table) {
            DB::statement("ALTER TABLE plan CHANGE COLUMN type `type` ENUM('DAILY', 'WEEKLY', 'MONTHLY','YEARLY') NOT NULL DEFAULT 'DAILY'");
            $table->dropColumn('show_homepage');
            $table->dropColumn('no_of_type');
        });
    }
}
