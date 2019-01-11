<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Indicator as IndicatorTrait;
use App\Core\V201\Traits\Forms\Result\Title as TitleTrait;
use App\Core\V201\Traits\Forms\Result\Description as DescriptionTrait;
use App\Core\V201\Traits\Forms\Result\Baseline as BaselineTrait;
use App\Core\V203\Traits\Forms\Result\Period as PeriodTrait;
use App\Core\V201\Traits\Forms\Result\Result as ResultTrait;

/**
 * Class Indicator
 * Contains the function to create the indicator form
 * @package App\Core\V202\Forms\Activity
 */
class Indicator extends BaseForm
{
    use ResultTrait, IndicatorTrait, TitleTrait, DescriptionTrait, BaselineTrait, PeriodTrait;

    /**
     * builds the activity indicator form
     */
    public function buildForm()
    {
        $this
            ->addMeasureList()
            ->addAscendingList()
            ->addAggregationStatusList()
            ->addTitles(['class' => 'indicator_title_title_narrative', 'narrative_true' => true])
            // ->addCollection('document_link', 'Activity\DocumentLink', 'document_link', [], trans('elementForm.document_link'))
            ->addDescriptions(['class' => 'indicator_description_title_narrative'])
            ->addCollection('reference', 'Activity\Reference', 'reference', [], trans('elementForm.reference'))
            ->addAddMoreButton('add_reference', 'reference')
            ->addCollection('baseline', 'Activity\Baseline', 'baseline', [], trans('elementForm.baseline'))
            ->addAddMoreButton('add_baseline', 'baseline')
            ->addPeriods()
            ->addAddMoreButton('add_period', 'period')
            ->addRemoveThisButton('remove_indicator');
    }
}
