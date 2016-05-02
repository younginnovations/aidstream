<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;
use Illuminate\Database\DatabaseManager;

class NarrativeForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $defaultFieldValues = app()->make(Databasemanager::class)->table('settings')->select('default_field_values')->where('organization_id', '=', session('org_id'))->first();
        $defaultLanguage    = $defaultFieldValues ? json_decode($defaultFieldValues->default_field_values, true)[0]['default_language'] : null;

        $this->add('narrative', 'textarea', ['label' => 'Text', 'help_block' => $this->addHelpText('Organisation_Name_Narrative-text'), 'attr' => ['rows' => 4], 'required' => true]);

        !(checkDataExists($this->model)) ?: $defaultLanguage = null;
        $this->addSelect('language', $this->getCodeList('Language', 'Organization'), 'Language', $this->addHelpText('Organisation_Name_Narrative-xml_lang'), $defaultLanguage);

        $this->addRemoveThisButton('remove');
    }
}
