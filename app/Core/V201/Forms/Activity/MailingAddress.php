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
            ->addNarrative('mailing_address_narrative')
            ->addAddMoreButton('add_mailing_address_narrative', 'mailing_address_narrative')
            ->addRemoveThisButton('remove_mailing_address');
    }
}
