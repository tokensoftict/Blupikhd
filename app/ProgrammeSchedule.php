<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $day
 * @property string $from
 * @property string $to
 * @property string $from_string
 * @property string $to_string
 * @property integer $from_number
 * @property integer $to_number
 * @property string $created_at
 * @property string $updated_at
 */

class ProgrammeSchedule extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'programme_schedule';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['title', 'description', 'day', 'from', 'link','to','status' ,'from_string', 'to_string', 'from_number', 'to_number','programme_image' ,'created_at', 'updated_at'];

    protected $appends = ['duration','programmeimageurl'];

    public function getDurationAttribute(){
        return $this->attributes['from_string']." To ".$this->attributes['to_string'];
    }

    public function getProgrammeimageurlAttribute(){
        if(!empty($this->attributes['programme_image'])) {
            return asset("/programme_image/".$this->attributes['programme_image']);
        }else{
            return '';
        }
    }
}
