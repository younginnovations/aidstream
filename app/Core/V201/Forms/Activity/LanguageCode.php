<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Models\Activity\Activity;

class LanguageCode extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $defaultLanguage = getDefaultLanguage();
        !(checkDataExists($this->model)) ?: $defaultLanguage = null;

        $this
            ->addSelect('language', $this->getCodeList('Language', 'Activity'), 'Language', $this->addHelpText('Activity_DocumentLink_Language-code'), $defaultLanguage, true)
            ->addRemoveThisButton('remove_language_code');
    }
}
