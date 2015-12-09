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
                    'choices'     => $this->getCodeList('ContactType', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Contact Type'
                ]
            )
            ->addCollection('organization', 'Activity\ContactInfoOrganization')
            ->addCollection('department', 'Activity\Department')
            ->addCollection('person_name', 'Activity\PersonName')
            ->addCollection('job_title', 'Activity\JobTitle')
            ->addCollection('telephone', 'Activity\Telephone', 'telephone')
            ->addAddMoreButton('add_telephone', 'telephone')
            ->addCollection('email', 'Activity\Email', 'email')
            ->addAddMoreButton('add_email', 'email')
            ->addCollection('website', 'Activity\Website', 'website')
            ->addAddMoreButton('add_website', 'website')
            ->addCollection('mailing_address', 'Activity\MailingAddress', 'mailingAddress')
            ->addAddMoreButton('add_mailingAddressNarrative', 'mailingAddress')
            ->addRemoveThisButton('remove_contact_info');
    }
}
