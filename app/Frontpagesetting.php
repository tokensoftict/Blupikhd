<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 */
class Frontpagesetting extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    protected $casts = [
        'value' => 'array',
    ];

    /**
     * @var array
     */
    protected $fillable = ['key', 'value', 'created_at', 'updated_at'];

}
