<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $approved_by
 * @property string $title
 * @property string $request_date
 * @property string $request_type
 * @property string $status
 * @property string $date_approved
 * @property string $ready_date
 * @property string $movie_description
 * @property string $movie_file
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property User $approved
 */
class MovieRequest extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'movie_request';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['user_id' ,'approved_by', 'title','comment' ,'request_date', 'request_type', 'status', 'date_approved', 'ready_date', 'movie_description', 'movie_file', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approved()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
