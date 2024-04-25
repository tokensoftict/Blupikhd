<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->enum("configured_ad_network",['facebook','goggle'])->after("paypal_client_id")->nullable();

            //settings for facebook
            $table->enum("facebook_ads_status",['TEST','LIVE'])->after("configured_ad_network")->nullable();
            $table->string("facebook_testingid_texting_ads_key")->after("configured_ad_network")->nullable();
            $table->string("facebook_Interstitial_placementid")->after("facebook_testingid_texting_ads_key")->nullable();
            $table->string("facebook_rewardedvideoad_placementid")->after("facebook_Interstitial_placementid")->nullable();
            $table->string("facebook_bannerAd")->after("facebook_rewardedvideoad_placementid")->nullable();

            $table->string("facebook_ios_Interstitial_placementid")->after("facebook_bannerAd")->nullable();
            $table->string("facebook_rewardedvideoad_ios_placementid")->after("facebook_ios_Interstitial_placementid")->nullable();
            $table->string("facebook_ios_bannerAd")->after("facebook_rewardedvideoad_ios_placementid")->nullable();

            //settings for google

            $table->enum("google_ads_status",['TEST','LIVE'])->after("facebook_ios_bannerAd")->nullable();
            $table->string("google_Interstitial_adunitid")->after("google_ads_status")->nullable();
            $table->string("google_rewardedvideoad_adunitid")->after("google_Interstitial_adunitid")->nullable();
            $table->string("google_bannerAd")->after("google_rewardedvideoad_adunitid")->nullable();


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
            $table->dropColumn("configured_ad_network");

            //settings for facebook
            $table->dropColumn("facebook_ads_status");
            $table->dropColumn("facebook_testingid_texting_ads_key");
            $table->dropColumn("facebook_Interstitial_placementid");
            $table->dropColumn("facebook_rewardedvideoad_placementid");
            $table->dropColumn("facebook_bannerAd");

            $table->dropColumn("facebook_ios_Interstitial_placementid");
            $table->dropColumn("facebook_rewardedvideoad_ios_placementid");
            $table->dropColumn("facebook_ios_bannerAd");

            //settings for google

            $table->dropColumn("google_ads_status");
            $table->dropColumn("google_Interstitial_adunitid");
            $table->dropColumn("google_rewardedvideoad_adunitid");
            $table->dropColumn("google_bannerAd");
        });
    }
}
