<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class RecipientOrgForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('ref', 'text', ['help_block' => $this->addHelpText('Organisation_RecipientOrgBudget_RecipientOrg-ref')])
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
