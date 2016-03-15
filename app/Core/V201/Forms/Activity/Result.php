<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Result as ResultTrait;
use App\Core\V201\Traits\Forms\Result\Title;
use App\Core\V201\Traits\Forms\Result\Description;
use App\Core\V201\Traits\Forms\Result\Indicator;

/**
 * Class Result
 * @package App\Core\V201\Forms\Activity
 */
class Result extends BaseForm
{
    use ResultTrait, Title, Description, Indicator;

    /**
     * builds the Activity Result form
     */
    public function buildForm()
    {
        $this
            ->addTypeList()
            ->addAggregationStatusList()
            ->addData(['narrative_true' => true])
            ->addTitles()
            ->addData(['narrative_true' => false])
            ->addDescriptions(['class' => 'description_narrative'])
            ->addIndicators()
            ->addAddMoreButton('add_indicator', 'indicator');
    }
}
