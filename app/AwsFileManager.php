<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $bucket_id
 * @property string $name
 * @property string $descriptions
 * @property string $aws_name
 * @property mixed $extra
 * @property string $created_at
 * @property string $updated_at
 */
class AwsFileManager extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'aws_file_manager';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['bucket_id', 'name', 'descriptions', 'aws_name', 'extra', 'created_at', 'updated_at'];

    public function awsBucket()
    {
        return $this->belongsTo(AwsBucket::class, 'bucket_id');
    }
}
