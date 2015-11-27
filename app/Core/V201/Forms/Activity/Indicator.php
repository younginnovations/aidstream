<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Indicator as IndicatorTrait;
use App\Core\V201\Traits\Forms\Result\Title;
use App\Core\V201\Traits\Forms\Result\Description;
use App\Core\V201\Traits\Forms\Result\Baseline;
use App\Core\V201\Traits\Forms\Result\Period;

/**
 * Class Indicator
 * Contains the function to create the indicator form
 * @package App\Core\V201\Forms\Activity
 */
class Indicator extends BaseForm
{
    use IndicatorTrait, Title, Description, Baseline, Period;

    /**
     * builds the activity indicator form
     */
    public function buildForm()
    {
        $this
            ->addMeasureList()
            ->addAscendingList()
            ->addTitles()
            ->addDescriptions()
            ->addBaselines()
            ->addPeriods()
            ->addRemoveThisButton('remove_indicator');
    }
}
