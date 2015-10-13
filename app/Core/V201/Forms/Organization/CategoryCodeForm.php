<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class CategoryCodeForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add(
                'Code',
                'select',
                [
                    'choices' => $this->addCodeList('DocumentCategory', 'Organization')
                ]
            )
            ->addRemoveThisButton('remove_category_code');
    }
}
