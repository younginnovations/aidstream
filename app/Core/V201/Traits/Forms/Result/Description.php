<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Description
 * @package App\Core\V201\Forms\Activity
 */
trait Description
{
    /**
     * Return description form
     * @return mixed
     */
    public function addDescriptions()
    {
        return $this->addCollection('description', 'Activity\Title');
    }
}
