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
            ->add('date', 'date', ['help_block' => $this->addHelpText('Activity_ActivityDate-iso_date')])
            ->addSelect('type', $this->getCodeList('ActivityDateType', 'Activity'), 'Activity Date Type', $this->addHelpText('Activity_ActivityDate-type'))
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_activity_date');
    }
}
