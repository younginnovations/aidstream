<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ActivityDate
 * @package App\Core\V201\Forms\Activity
 */
class ActivityDate extends BaseForm
{
    /**
     * builds activity activity date form
     */
    public function buildForm()
    {
        $this
            ->add('date', 'date')
            ->add(
                'type',
                'select',
                [
                    'choices' => $this->addCodeList('ActivityDateType', 'Activity'),
                    'label' => 'Activity Date Type'
                ]
            )
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_activty_date');
    }
}
