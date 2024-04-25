<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property string $payload
 * @property string $device_ids
 * @property integer $no_of_device
 * @property string $type
 * @property integer $total_view
 * @property integer $total_sent
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class PushMessage extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'push_message';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['title', 'body', 'payload', 'device_ids', 'no_of_device', 'type', 'total_view', 'total_sent', 'status', 'created_at', 'updated_at'];

}
