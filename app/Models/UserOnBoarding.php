<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOnBoarding extends Model
{
    protected $fillable = ['user_id', 'completed_tour', 'has_logged_in_once', 'completed_steps'];

    protected $table = 'user_onboarding';

    protected $casts = [
        'completed_steps' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
