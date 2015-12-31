<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class RecipientOrgForm extends BaseForm
{
    public function buildForm()
    {
        $this->add('Ref', 'text', ['help_block' => $this->addHelpText('Organisation_RecipientOrgBudget_RecipientOrg-ref')]);
    }
}
