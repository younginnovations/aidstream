<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Models\Activity\Activity;

class LanguageCode extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->addSelect('language', $this->getCodeList('Language', 'Activity'), trans('elementForm.language'), $this->addHelpText('Activity_DocumentLink_Language-code'))
            ->addRemoveThisButton('remove_language_code');
    }
}
