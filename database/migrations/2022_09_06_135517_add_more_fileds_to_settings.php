<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFiledsToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->enum("ios_ads_status",['TEST','LIVE'])->after("google_ads_status")->nullable();
            $table->string("ios_Interstitial_adunitid")->nullable()->after("ios_ads_status")->nullable();
            $table->string("ios_rewardedvideoad_adunitid")->nullable()->after("ios_Interstitial_adunitid")->nullable();
            $table->string("ios_bannerAd")->nullable()->after("ios_rewardedvideoad_adunitid")->nullable();
            $table->text('terms_condition')->nullable()->after('ios_bannerAd');
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
            $table->dropColumn(['ios_ads_status','ios_Interstitial_adunitid','ios_rewardedvideoad_adunitid','ios_bannerAd','terms_condition']);
        });
    }
}
