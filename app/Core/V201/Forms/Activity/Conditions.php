<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Conditions
 * @package App\Core\V201\Forms\Activity
 */
class Conditions extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addSelect('condition_attached', ['0' => 'No', '1' => 'Yes'], 'Condition Attached', $this->addHelpText('Activity_Conditions-attached'), null, true)
            ->addCollection('condition', 'Activity\Condition', 'condition')
            ->addAddMoreButton('add_condition', 'condition');
    }
}
