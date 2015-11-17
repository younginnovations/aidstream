<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Period
 * @package App\Core\V201\Forms\Activity
 */
trait Period
{
    /**
     * Return period form
     * @return mixed
     */
    public function addPeriods()
    {
        return $this->addCollection('period', 'Activity\Period');
    }

    /**
     * Return period start form
     * @return mixed
     */
    public function addPeriodStart()
    {
        return $this->addCollection('period_start', 'Activity\PeriodDate');
    }

    /**
     * Return period end form
     * @return mixed
     */
    public function addPeriodEnd()
    {
        return $this->addCollection('period_end', 'Activity\PeriodDate');
    }
}
