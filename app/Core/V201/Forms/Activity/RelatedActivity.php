<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class RelatedActivity
 * @package App\Core\V201\Forms\Activity
 */
class RelatedActivity extends BaseForm
{
    /**
     * builds activity activity date form
     */
    public function buildForm()
    {
        $this
            ->addSelect('relationship_type', $this->getCodeList('RelatedActivityType', 'Activity'), 'Type of Relationship', $this->addHelpText('Activity_RelatedActivity-type'), null, true)
            ->add('activity_identifier', 'text', ['help_block' => $this->addHelpText('Activity_RelatedActivity-ref'), 'required' => true])
            ->addRemoveThisButton('remove');
    }
}
