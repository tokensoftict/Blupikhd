<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['firstname', 'lastname', 'oauth_type','username', 'phoneno', 'password', 'role', 'email', 'device_key', 'device_push_token', 'country_id', 'state_id', 'wallet', 'subscription_expired_timestamp', 'subscription_begin_timestamp', 'email_verified_at', 'remember_token', 'created_at', 'updated_at'];

    protected $appends = array('walletamt','wallet_currency','expirymessage','substatus');
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
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


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
    
    
     /**
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

}
