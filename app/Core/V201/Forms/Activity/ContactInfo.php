<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ContactInfo
 * @package App\Core\V201\Forms\Activity
 */
class ContactInfo extends BaseForm
{
    /**
     * builds activity Contact Info form
     */
    public function buildForm()
    {
        $this
            ->add(
                'type',
                'select',
                [
                    'choices' => $this->addCodeList('ContactType', 'Activity'),
                    'label' => 'Contact Type'
                ]
            )
            ->addCollection('organization', 'Activity\ContactInfoOrganization')
            ->addCollection('department', 'Activity\Department')
            ->addCollection('person_name', 'Activity\PersonName')
            ->addCollection('job_title', 'Activity\JobTitle')
            ->addCollection('telephone', 'Activity\Telephone', 'telephoneNarrative')
            ->addAddMoreButton('add_telephoneNarrative', 'telephoneNarrative')
            ->addCollection('email', 'Activity\Email', 'emailNarrative')
            ->addAddMoreButton('add_emailNarrative', 'emailNarrative')
            ->addCollection('website', 'Activity\Website', 'websiteNarrative')
            ->addAddMoreButton('add_websiteNarrative', 'websiteNarrative')
            ->addCollection('mailing_address', 'Activity\MailingAddress', 'mailingAddressNarrative')
            ->addAddMoreButton('add_mailingAddressNarrative', 'mailingAddressNarrative')
            ->addRemoveThisButton('remove_contact_info');
    }
}
