<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property int $parent_id
 * @property int $order
 * @property string $name
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 */
class Category extends Model
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
    protected $fillable = ['parent_id', 'order', 'name', 'slug', 'created_at', 'updated_at'];

}
