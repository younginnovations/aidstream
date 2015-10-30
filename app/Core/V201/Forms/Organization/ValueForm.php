<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class ValueForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('amount', 'text')
            ->add(
                'currency',
                'select',
                [
                    'choices' => $this->getCodeList('Currency', 'Organization'),
                    'label'   => 'Currency'
                ]
            )
            ->add('value_date', 'date');
    }
}
