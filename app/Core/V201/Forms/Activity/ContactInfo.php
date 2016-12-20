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
            ->addSelect('type', $this->getCodeList('ContactType', 'Activity'), trans('elementForm.contact_type'), $this->addHelpText('Activity_ContactInfo-type'))
            ->addCollection('organization', 'Activity\ContactInfoOrganization', '', [], trans('elementForm.organisation'))
            ->addCollection('department', 'Activity\Department', '', [], trans('elementForm.department'))
            ->addCollection('person_name', 'Activity\PersonName', '', [], trans('elementForm.person_name'))
            ->addCollection('job_title', 'Activity\JobTitle', '', [], trans('elementForm.job_title'))
            ->addCollection('telephone', 'Activity\Telephone', 'telephone', [], trans('elementForm.telephone'))
            ->addAddMoreButton('add_telephone', 'telephone')
            ->addCollection('email', 'Activity\Email', 'email', [], trans('elementForm.email'))
            ->addAddMoreButton('add_email', 'email')
            ->addCollection('website', 'Activity\Website', 'website', [], trans('elementForm.website'))
            ->addAddMoreButton('add_website', 'website')
            ->addCollection('mailing_address', 'Activity\MailingAddress', 'mailingAddress', [], trans('elementForm.mailing_address'))
            ->addAddMoreButton('add_mailingAddressNarrative', 'mailingAddress')
            ->addRemoveThisButton('remove_contact_info');
    }
}
