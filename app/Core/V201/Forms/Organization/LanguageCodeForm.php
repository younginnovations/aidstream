<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;
use Illuminate\Database\DatabaseManager;

class LanguageCodeForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->addSelect('language', $this->getCodeList('Language', 'Organization'), 'Language', $this->addHelpText('Organisation_DocumentLink_Language-code'))
            ->addRemoveThisButton('remove_language_code');
    }
}
