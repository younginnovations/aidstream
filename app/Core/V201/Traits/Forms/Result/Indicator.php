<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Indicator
 * @package App\Core\V201\Forms\Activity
 */
trait Indicator
{
    /**
     * Return indicator form
     * @return mixed
     */
    public function addIndicators()
    {
        return $this->addCollection('indicator', 'Activity\Indicator', 'indicator');
    }

    /**
     * Return measure field
     * @return mixed
     */
    public function addMeasureList()
    {
        return $this->addSelect('measure', $this->getCodeList('IndicatorMeasure', 'Activity'));
    }

    /**
     * Return ascending field
     * @return mixed
     */
    public function addAscendingList()
    {
        return $this->addSelect('ascending', [0 => 'False', 1 => 'True']);
    }
}
