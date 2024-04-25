<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property float $amount
 * @property string $status
 * @property string $description
 * @property string $web_description
 * @property string $created_at
 * @property string $updated_at
 */
class Plan extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'plan';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'type', 'amount', 'web_description','status', 'description', 'show_homepage','created_at','no_of_type' ,'updated_at'];


    protected $appends = ['amount_str'];


    public function getAmountStrAttribute(){
        return Setting::find(1)->currency." ".number_format($this->attributes['amount'],2);
    }
}
