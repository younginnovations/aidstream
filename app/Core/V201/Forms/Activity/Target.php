<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Comment;

/**
 * Class Target
 * Contains the function to create the target form
 * @package App\Core\V201\Forms\Activity
 */
class Target extends BaseForm
{
    use Comment;

    /**
     * builds the activity target form
     */
    public function buildForm()
    {
        $this
            ->add('value', 'text', ['help_block' => $this->addHelpText('Activity_Result_Indicator_Period_Target-value')])
            ->addComments($this->getData());
    }
}
