<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DateTime;

/**
 * @property integer $id
 * @property string $title
 * @property string $excerpt
 * @property string $content_text
 * @property string $content_html
 * @property string $image
 * @property string $slug
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $status
 * @property int $category
 * @property string $published_at
 * @property string $created_at
 * @property string $updated_at
 * @property PostComment[] $postComments
 * @property PostTag[] $postTags
 */
class Post extends Model
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
    protected $fillable = ['total_view','title', 'excerpt', 'content_text', 'content_html', 'image', 'slug', 'seo_title', 'seo_description', 'seo_keywords', 'status', 'category', 'published_at', 'created_at', 'updated_at'];

    protected $appends = array('blogimageurl','poster_name','ago','permalink');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postTags()
    {
        return $this->hasMany('App\PostTag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postComments()
    {
        return $this->hasMany('App\PostComment');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        if (! $this->exists) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function getBlogimageurlAttribute(){
        if(!empty($this->attributes['image'])) {
            return asset("/post_image/".$this->attributes['image']);
        }else{
            return '';
        }
    }
    public function getPosternameAttribute(){
        return User::where("role","ADMIN")->first()->firstname." ".User::where("role","ADMIN")->first()->lastname;
    }

    public function getAgoAttribute(){
        return $this->time_elapsed_string($this->attributes['created_at']);
    }

    public function getPermalinkAttribute(){
        return url("blogs/".$this->attributes['slug']);
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
