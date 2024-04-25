<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $post_id
 * @property integer $tag_id
 * @property string $created_at
 * @property string $updated_at
 * @property Post $post
 * @property Tag $tag
 */
class PostTag extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'post_tag';

    /**
     * @var array
     */
    protected $fillable = ['post_id', 'tag_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }
}
