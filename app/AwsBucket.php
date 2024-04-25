<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $key
 * @property string $descriptions
 * @property mixed $extra
 * @property string $created_at
 * @property string $updated_at
 */
class AwsBucket extends Model
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
    protected $fillable = ['name', 'key', 'descriptions', 'extra', 'created_at', 'updated_at'];

    public function files()
    {
        return $this->hasMany(AwsFileManager::class, 'bucket_id');
    }
}
