<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class CategoryCode extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->addSelect('code', $this->getCodeList('DocumentCategory', 'Activity'), 'Code', $this->addHelpText('Activity_DocumentLink_Category-code'), null, true)
            ->addRemoveThisButton('remove_category_code');
    }
}
