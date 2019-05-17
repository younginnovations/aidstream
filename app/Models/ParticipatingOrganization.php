<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipatingOrganization extends Model
{
    protected $table = "participating_organizations";
    protected $fillable = [
        'name',
        'country_code',
        'type',
        'identifier'
    ];
}
