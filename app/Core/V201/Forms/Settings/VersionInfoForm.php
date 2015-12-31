<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

class VersionInfoForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this->addSelect('version', $this->getData('versions'), 'Select Version');
    }
}
