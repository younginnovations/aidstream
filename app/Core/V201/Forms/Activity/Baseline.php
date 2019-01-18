<?php namespace App\Core\V201\Forms\Activity;

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
            ->add('value', 'text', ['label' => trans('elementForm.value'), 'help_block' => $this->addHelpText('Activity_Result_Indicator_Baseline-value'), 'required' => true])
            ->addComments(['class' => 'indicator_baseline_comment_title_narrative']);
    }
}
