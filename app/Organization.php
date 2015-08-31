<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model {

    protected $fillable = ['name', 'address', 'user_identifier'];

}
