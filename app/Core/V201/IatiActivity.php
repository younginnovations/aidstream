<?php namespace App\Core\V201;

use App;

/**
 * Class IatiActivity
 * @package App\Core\V201
 */
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

    public function getTitle()
    {
        return app('App\Core\V201\Element\Activity\Title');
    }

    public function getTitleRequest()
    {
        return app('App\Core\V201\Requests\Activity\Title');
    }

    public function getDescription()
    {
        return app('App\Core\V201\Element\Activity\Description');
    }

    public function getDescriptionRequest()
    {
        return app('App\Core\V201\Requests\Activity\Description');
    }
}
