<?php namespace App\Core\V201\Traits\Forms\Result;

use App\Core\V201\Forms\Activity\Result as ResultForm;

/**
 * Class Indicator
 * @package App\Core\V201\Forms\Activity
 */
trait Indicator
{
    /**
     * Return indicator form
     * @return ResultForm
     */
    public function addIndicators()
    {
        return $this->addCollection('indicator', 'Activity\Indicator', 'indicator');
    }

    /**
     * Return measure field
     * @return ResultForm
     */
    public function addMeasureList()
    {
        return $this->addSelect('measure', $this->getCodeList('IndicatorMeasure', 'Activity'), 'Measure', $this->addHelpText('Activity_Result_Indicator-measure'), null, true);
    }

    /**
     * Return ascending field
     * @return ResultForm
     */
    public function addAscendingList()
    {
        return $this->addSelect('ascending', [0 => 'False', 1 => 'True'], 'Ascending', $this->addHelpText('Activity_Result_Indicator-ascending'));
    }
}
