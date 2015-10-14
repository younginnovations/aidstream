<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MailingAddress
 * @package App\Core\V201\Forms\Activity
 */
class MailingAddress extends BaseForm
{
    /**
     * builds the contact info Mailing Address form
     */
    public function buildForm()
    {
        $this
            ->addNarrative('mailingAddressNarrative')
            ->addAddMoreButton('add_mailingAddressNarrative', 'mailingAddressNarrative')
            ->addRemoveThisButton('remove_mailing_address');
    }
}
