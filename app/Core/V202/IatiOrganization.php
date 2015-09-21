<?php
namespace App\Core\V202;

use App\Core\V201\IatiOrganization as V201;
use App;

class IatiOrganization extends V201
{
    public function getNarrative()
    {
        return App::make('App\Core\V202\Element\Organization\Narrative');
    }

    public function getName()
    {
        return App::make('App\Core\V202\Element\Organization\Name');
    }

    public function getNameRequestRequest()
    {
        return App::make('App\Core\V202\Requests\Organization\CreateNameRequest');
    }
}