<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class OtherIdentifier
 * Activity other identifier form to collect activity other identifier
 * @package App\Core\V201\Forms\Activity
 */
class OtherIdentifier extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('reference', 'text', ['help_block' => $this->addHelpText('Activity_OtherActivityIdentifier-ref'), 'required' => true])
            ->addSelect('type', $this->getCodeList('OtherIdentifierType', 'Activity'), 'Type', $this->addHelpText('Activity_OtherActivityIdentifier-type'), null, true)
            ->addCollection('owner_org', 'Activity\OwnerOrg', 'owner_organization', [], 'Owner Organisation')
            ->addRemoveThisButton('remove_other_identifier');
    }
}
