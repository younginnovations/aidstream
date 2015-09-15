<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserActivity
 * @package App\Models
 */
class UserActivity extends Model
{

    /**
     * @var string
     */
    protected $table = 'user_activities';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'action', 'param'];


    /**
     * @param $param
     * @return array
     */
    public function getParamAttribute($param)
    {
        if (is_null($param)) {
            return [];
        }

        return json_decode($param, true);
    }

    /**
     * @param $param
     */
    public function setParamAttribute($param)
    {
        $this->attributes['param'] = json_encode($param);
    }

}
