<?php
namespace App\Core\V201;

use App;

class IatiActivity
{

    public function getIdentifier()
    {
        return app('App\Core\V201\Element\Activity\Identifier');
    }

    public function getRepository()
    {
        return app('App\Core\V201\Repositories\Activity\ActivityRepository');
    }

    public function getIatiIdentifierRequest()
    {
        return app('App\Core\V201\Requests\Activity\IatiIdentifierRequest');
    }

    public function getOtherIdentifier()
    {
        return app('App\Core\V201\Element\Activity\OtherIdentifier');
    }

    public function getOtherIdentifierRequest()
    {
        return app('App\Core\V201\Requests\Activity\OtherIdentifierRequest');
    }

}
