<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Comment;

/**
 * Class Baseline
 * Contains the function to create the title form
 * @package App\Core\V201\Forms\Activity
 */
class Baseline extends BaseForm
{
    use Comment;

    /**
     * builds the activity title form
     */
    public function buildForm()
    {
        $this
            ->add('year', 'text', ['label' => trans('elementForm.year'), 'help_block' => $this->addHelpText('Activity_Result_Indicator_Baseline-year'), 'required' => true])
            ->add('date','date',['label' => trans('elementForm.date'), 'help_block' => $this->addHelpText('Activity_Budget_PeriodStart-iso_date'), 'required' => false, 'attr' => ['placeholder' => 'YYYY-MM-DD']])
            ->add('value', 'text', ['label' => trans('elementForm.value'), 'help_block' => $this->addHelpText('Activity_Result_Indicator_Baseline-value')])
            ->addCollection('ref', 'Activity\ResultLocation', 'location', [], trans('elementForm.location'))
            ->addAddMoreButton('add_location', 'location')
            ->addCollection('dimension', 'Activity\Dimension','dimension', [], trans('elementForm.dimension'))
            ->addAddMoreButton('add_dimension','dimension')
            ->addComments(['class' => 'indicator_baseline_comment_title_narrative']);
    }
}
