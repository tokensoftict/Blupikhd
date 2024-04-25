<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $app_name
 * @property string $contact_page
 * @property string $access_mode
 * @property string $phone_number
 * @property string $app_logo
 * @property string $paypal_seller_email
 * @property string $paypal_status
 * @property string $paypal_mode
 * @property string $paypal_client_id
 * @property string $apple_play_status
 * @property string $google_play_status
 * @property string $configured_ad_network
 * @property string $facebook_testingid_texting_ads_key
 * @property string $facebook_Interstitial_placementid
 * @property string $facebook_rewardedvideoad_placementid
 * @property string $facebook_bannerAd
 * @property string $facebook_ios_Interstitial_placementid
 * @property string $facebook_rewardedvideoad_ios_placementid
 * @property string $facebook_ios_bannerAd
 * @property string $google_ads_status
 * @property string $google_Interstitial_adunitid
 * @property string $google_rewardedvideoad_adunitid
 * @property string $google_bannerAd
 * @property string $facebook_ads_status
 * @property string $stripe_status
 * @property string $stripe_api_key
 * @property string $stripe_public_key
 * @property string $created_at
 * @property string $updated_at
 */
class Setting extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['app_name','facebook_token','paypal_client_secret','apple_play_status', 'google_play_status','streamning_url','contact_page', 'access_mode', 'phone_number', 'app_logo', 'paypal_seller_email', 'paypal_status', 'paypal_mode', 'paypal_client_id', 'configured_ad_network', 'facebook_testingid_texting_ads_key', 'facebook_Interstitial_placementid', 'facebook_rewardedvideoad_placementid', 'facebook_bannerAd', 'facebook_ios_Interstitial_placementid', 'facebook_rewardedvideoad_ios_placementid', 'facebook_ios_bannerAd', 'google_ads_status', 'google_Interstitial_adunitid', 'google_rewardedvideoad_adunitid', 'google_bannerAd', 'facebook_ads_status', 'stripe_status', 'stripe_api_key', 'stripe_public_key',
        'created_at', 'updated_at','ios_ads_status','ios_Interstitial_adunitid','ios_rewardedvideoad_adunitid','ios_bannerAd','terms_condition'];

    public static $keys = ['app_name','facebook_token','paypal_client_secret','apple_play_status', 'google_play_status','streamning_url','contact_page', 'access_mode', 'phone_number', 'app_logo', 'paypal_seller_email', 'paypal_status', 'paypal_mode', 'paypal_client_id', 'configured_ad_network', 'facebook_testingid_texting_ads_key', 'facebook_Interstitial_placementid', 'facebook_rewardedvideoad_placementid', 'facebook_bannerAd', 'facebook_ios_Interstitial_placementid', 'facebook_rewardedvideoad_ios_placementid', 'facebook_ios_bannerAd', 'google_ads_status', 'google_Interstitial_adunitid', 'google_rewardedvideoad_adunitid', 'google_bannerAd', 'facebook_ads_status', 'stripe_status', 'stripe_api_key', 'stripe_public_key'
        ,'ios_ads_status','ios_Interstitial_adunitid','ios_rewardedvideoad_adunitid','ios_bannerAd','terms_condition'
    ];


    protected $appends = ['currency'];

    public function getCurrencyAttribute(){
        return "USD";
    }
}
