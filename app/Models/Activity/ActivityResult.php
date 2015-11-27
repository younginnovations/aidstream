<?php namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityResult
 * @package App\Models\Activity
 */
class ActivityResult extends Model
{
    protected $fillable = [
        'activity_id',
        'result'
    ];

    protected $casts = [
        'result' => 'json'
    ];

    /**
     * get result title
     * @return string
     */
    public function getTitleAttribute()
    {
        $title = $this->result['title'][0]['narrative'][0]['narrative'];

        return $title == "" ? 'No Title' : $title;
    }

    /**
     * get result type
     * @return string
     */
    public function getTypeAttribute()
    {
        return $this->result['type'];
    }
}
