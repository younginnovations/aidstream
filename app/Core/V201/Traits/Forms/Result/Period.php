<?php namespace App\Core\V201\Traits\Forms\Result;

use App\Core\V201\Forms\Activity\Result as ResultForm;

/**
 * Class Period
 * @package App\Core\V201\Forms\Activity
 */
trait Period
{
    /**
     * Return period form
     * @return ResultForm
     */
    public function addPeriods()
    {
        return $this->addCollection('period', 'Activity\Period', 'period', [], trans('elementForm.period'));
    }

    /**
     * Return period start form
     * @return ResultForm
     */
    public function addPeriodStart()
    {
        return $this->addCollection('period_start', 'Activity\PeriodDate', '', [], trans('elementForm.period_start'));
    }

    /**
     * Return period end form
     * @return ResultForm
     */
    public function addPeriodEnd()
    {
        return $this->addCollection('period_end', 'Activity\PeriodDate', '', [], trans('elementForm.period_end'));
    }
}
