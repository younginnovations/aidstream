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
                    'choices' => $this->getCodeList('DocumentCategory', 'Activity')
                ]
            )
            ->addRemoveThisButton('remove_category_code');
    }
}
