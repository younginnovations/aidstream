<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class CategoryCode extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add(
                'code',
                'select',
                [
                    'choices'     => $this->getCodeList('DocumentCategory', 'Activity'),
                    'empty_value' => 'Select one of the following option :'
                ]
            )
            ->addRemoveThisButton('remove_category_code');
    }
}
