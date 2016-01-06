<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class NarrativeForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('narrative', 'text', ['label' => 'Text', 'help_block' => $this->addHelpText('Organisation_Name_Narrative-text')])
            ->addSelect('language', $this->getCodeList('Language', 'Organization'), 'Language', $this->addHelpText('Organisation_Name_Narrative-xml_lang'))
            ->addRemoveThisButton('remove');
    }
}
