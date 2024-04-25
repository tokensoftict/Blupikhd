<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property float $amount
 * @property string $status
 * @property string $transaction_token
 * @property string $transaction_date
 * @property string $firstname
 * @property string $lastname
 * @property string $country
 * @property string $state
 * @property string $phoneno
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 */
class TopupTransaction extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'topup_transaction';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['user_id','payment_gateway' ,'amount', 'status', 'transaction_token', 'transaction_date', 'firstname', 'lastname', 'country', 'state', 'phoneno', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
