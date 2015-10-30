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
            ->add('reference', 'text')
            ->add(
                'type',
                'select',
                [
                    'choices' => $this->getCodeList('OtherIdentifierType', 'Activity'),
                    'label'   => 'Type'
                ]
            )
            ->addCollection('owner_org', 'Activity\OwnerOrg', 'owner_organization')
            ->addRemoveThisButton('remove_other_identifier');
    }
}
