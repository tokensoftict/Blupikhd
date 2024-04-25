<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $post_id
 * @property integer $user_id
 * @property string $content_html
 * @property string $status
 * @property string $post_date
 * @property integer $timestamp
 * @property string $created_at
 * @property string $updated_at
 * @property Post $post
 * @property User $user
 */
class PostComment extends Model
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
    protected $fillable = ['post_id', 'user_id', 'content_html', 'status', 'post_date', 'timestamp', 'created_at', 'updated_at'];

    protected $appends = array('name','ago');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function getNameAttribute(){
        return $this->user->firstname." ".$this->user->lastname;
    }

    public function getAgoAttribute(){
        return $this->time_elapsed_string($this->attributes['created_at']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
