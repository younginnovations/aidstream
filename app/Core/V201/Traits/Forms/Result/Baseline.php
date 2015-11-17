<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Baseline
 * @package App\Core\V201\Forms\Activity
 */
trait Baseline
{
    /**
     * Return baseline form
     * @return mixed
     */
    public function addBaselines()
    {
        return $this->addCollection('baseline', 'Activity\Baseline');
    }
}
