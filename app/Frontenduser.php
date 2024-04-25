<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $username
 * @property string $phoneno
 * @property string $password
 * @property string $role
 * @property string $email
 * @property string $device_key
 * @property string $device_push_token
 * @property integer $country_id
 * @property integer $state_id
 * @property float $wallet
 * @property integer $subscription_expired_timestamp
 * @property integer $subscription_begin_timestamp
 * @property string $email_verified_at
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property MovieRequest[] $movieRequests
 * @property MovieRequest[] $adminMovieRequests
 * @property PostComment[] $postComments
 * @property TopupTransaction[] $topupTransactions
 * @property Transaction[] $transactions
 * @property Transaction[] $recipientTransactions
 */
class Frontenduser extends Authenticatable
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'users';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['firstname', 'lastname', 'username', 'oauth_type','phoneno', 'password', 'role', 'email', 'device_key', 'device_push_token', 'country_id', 'state_id', 'wallet', 'subscription_expired_timestamp', 'subscription_begin_timestamp', 'email_verified_at', 'remember_token', 'created_at', 'updated_at'];

    protected $appends = array('walletamt','wallet_currency','expirymessage','substatus');


    public function getExpirymessageAttribute(){
        if(empty($this->attributes['subscription_expired_timestamp'])){
            return "You do not have an active subscription";
        }else if(time() > $this->attributes['subscription_expired_timestamp']){
            return "Your Subscription plan has expired";
        }else if(time() < $this->attributes['subscription_expired_timestamp']){
            return "Your Subscription will expired on ". date("l jS \of F Y h:i:s A",$this->attributes['subscription_expired_timestamp']);
        }
        return "Unknown error occurred";
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adminMovieRequests()
    {
        return $this->hasMany('App\MovieRequest', 'approved_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movieRequests()
    {
        return $this->hasMany('App\MovieRequest');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postComments()
    {
        return $this->hasMany('App\PostComment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topupTransactions()
    {
        return $this->hasMany('App\TopupTransaction');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipientTransactions()
    {
        return $this->hasMany('App\Transaction', 'recipient_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }


    public function getWalletAttribute(){
        if(empty($this->attributes['wallet'])){
            return 0;
        }
        return $this->attributes['wallet'];
    }

    public function getWalletamtAttribute(){
        if(empty($this->attributes['wallet'])){
            return number_format(0,2);
        }else{
            return number_format($this->attributes['wallet'],2);
        }
    }

    public function getWalletCurrencyAttribute(){
        return  Setting::find(1)->currency;
    }


    public function getSubstatusAttribute(){
        if(empty($this->attributes['subscription_expired_timestamp'])){
            return false;
        }else if(time() < $this->attributes['subscription_expired_timestamp']){
            return true;
        }else if(time() > $this->attributes['subscription_expired_timestamp']){
            return false;
        }else{
            return false;
        }
    }
}
