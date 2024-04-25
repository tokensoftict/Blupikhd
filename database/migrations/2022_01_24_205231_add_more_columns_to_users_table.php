<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->after('device_push_token')->nullable();
            $table->string('facebook_id')->after('google_id')->nullable();
            $table->string('twitter_id')->after('facebook_id')->nullable();
            $table->string('oauth_type')->after('twitter_id')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
            $table->dropColumn('facebook_id');
            $table->dropColumn('twitter_id');
            $table->dropColumn('oauth_type');
        });
    }
}
