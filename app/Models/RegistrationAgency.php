<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegistrationAgency
 * @package App\Models
 */
class RegistrationAgency extends Model
{
    protected $table = 'registration_agencies';
    protected $fillable = ['org_id', 'country', 'short_form', 'name', 'website', 'moderated'];
}
