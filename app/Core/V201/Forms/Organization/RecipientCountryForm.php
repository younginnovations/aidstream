<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class RecipientCountryForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addSelect('code', $this->getCodeList('Country', 'Organization'), 'Code', $this->addHelpText('Organisation_RecipientCountryBudget_RecipientCountry-code'))
            ->addNarrative('recipient_country_narrative')
            ->addAddMoreButton('add_recipient_country_narrative', 'recipient_country_narrative')
            ->addRemoveThisButton('remove_recipient_country');
    }
}
