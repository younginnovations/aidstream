<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Targets
 * @package App\Core\V201\Forms\Activity
 */
trait Targets
{
    /**
     * Return target form
     * @return mixed
     */
    public function addTargets()
    {
        return $this->addCollection('target', 'Activity\Target');
    }

    /**
     * Return actual target form
     * @return mixed
     */
    public function addActualTargets()
    {
        return $this->addCollection('actual', 'Activity\Target');
    }
}
