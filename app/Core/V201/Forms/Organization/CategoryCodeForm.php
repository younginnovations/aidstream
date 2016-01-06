<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class CategoryCodeForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->addSelect('code', $this->getCodeList('DocumentCategory', 'Organization'), 'Code', $this->addHelpText('Organisation_DocumentLink_Category-code'))
            ->addRemoveThisButton('remove_category_code');
    }
}
