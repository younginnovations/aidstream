<?php namespace app\Core\V201\Element\Activity;

/**
 * Class ParticipatingOrganization
 * @package app\Core\V201\Element\Activity
 */
class ParticipatingOrganization
{
    /**
     * @return  Participating Organization form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleParticipatingOrganization";
    }

    /**
     * @return Participating Organization repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\ParticipatingOrganization');
    }
}
