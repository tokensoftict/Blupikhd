<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $recipient_id
 * @property string $trans_type
 * @property string $mode
 * @property string $sign
 * @property float $amount
 * @property string $transaction_date
 * @property string $description
 * @property string $currency
 * @property float $wallet_amt_before
 * @property float $wallet_amt_after
 * @property string $created_at
 * @property string $updated_at
 * @property User $recipient
 * @property User $user
 */
class Transaction extends Model
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
    protected $fillable = ['user_id','currency' ,'recipient_id', 'trans_type', 'sign', 'amount', 'transaction_date', 'description', 'mode','wallet_amt_before', 'wallet_amt_after', 'created_at', 'updated_at'];

    protected $appends = ['transaction_date_format','format_amount'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo('App\User', 'recipient_id');
    }

    public function getTransactionDateFormatAttribute(){
        return str_date($this->attributes['transaction_date']);
    }

    public function getFormatAmountAttribute(){
        return $this->attributes['currency']." ".$this->attributes['sign'].number_format($this->attributes['amount'],2);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
