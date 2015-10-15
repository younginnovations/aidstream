<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class RecipientCountryForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'code',
                'select',
                [
                    'choices' => $this->addCodeList('Country', 'Organization'),
                    'label' => 'Code'
                ]
            )
            ->addNarrative('recipient_country_narrative')
            ->addAddMoreButton('add_recipient_country_narrative', 'recipient_country_narrative')
            ->addRemoveThisButton('remove_recipient_country');
    }
}
