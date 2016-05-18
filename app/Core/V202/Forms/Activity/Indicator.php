<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Indicator as IndicatorTrait;
use App\Core\V201\Traits\Forms\Result\Title as TitleTrait;
use App\Core\V201\Traits\Forms\Result\Description as DescriptionTrait;
use App\Core\V201\Traits\Forms\Result\Baseline as BaselineTrait;
use App\Core\V201\Traits\Forms\Result\Period as PeriodTrait;

/**
 * Class Indicator
 * Contains the function to create the indicator form
 * @package App\Core\V202\Forms\Activity
 */
class Indicator extends BaseForm
{
    use IndicatorTrait, TitleTrait, DescriptionTrait, BaselineTrait, PeriodTrait;

    /**
     * builds the activity indicator form
     */
    public function buildForm()
    {
        $this
            ->addMeasureList()
            ->addAscendingList()
            ->addTitles(['class' => 'indicator_title_title_narrative', 'narrative_true' => true])
            ->addDescriptions(['class' => 'indicator_description_title_narrative'])
            ->addCollection('reference', 'Activity\Reference', 'reference')
            ->addAddMoreButton('add_reference', 'reference')
            ->addBaselines()
            ->addPeriods()
            ->addAddMoreButton('add_period', 'period')
            ->addRemoveThisButton('remove_indicator');
    }
}
