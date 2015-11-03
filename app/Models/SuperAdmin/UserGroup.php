<?php namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table    = "user_group";
    protected $fillable = ['group_name', 'group_identifier', 'assigned_organizations', 'user_id'];
    protected $casts    = ['assigned_organizations' => 'json'];

    /**
     * userGroup belongs to user
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
