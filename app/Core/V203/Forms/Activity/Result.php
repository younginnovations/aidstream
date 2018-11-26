<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Baseline;
use App\Core\V201\Traits\Forms\Result\Comment;
use App\Core\V203\Traits\Forms\Result\Period;
use App\Core\V203\Traits\Forms\Result\Result as ResultTrait;
use App\Core\V203\Traits\Forms\Result\Targets;
use App\Core\V201\Traits\Forms\Result\Title;
use App\Core\V201\Traits\Forms\Result\Description;
use App\Core\V203\Traits\Forms\Result\Indicator;

/**
 * Class Result
 * @package App\Core\V201\Forms\Activity
 */
class Result extends BaseForm
{
    use ResultTrait, Title, Description, Indicator, Baseline, Comment, Period, Targets;

    /**
     * builds the Activity Result form
     */
    public function buildForm()
    {
        $this
            ->addTypeList()
            ->addAggregationStatusList()
            ->addTitles(['class' => 'narrative', 'narrative_true' => true])
            ->addDescriptions(['class' => 'description_narrative'])
            ->addCollection('document_link', 'Activity\DocumentLink','document_link')
            ->addAddMoreButton('add_document_link', 'document_link')
            ->addCollection('reference','Activity\ResultReference', '', [], trans('elementForm.reference'))
            ->addIndicators()
            ->addAddMoreButton('add_indicator', 'indicator');
    }
}
