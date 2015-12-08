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
                    'choices'     => $this->getCodeList('Currency', 'Organization'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Currency'
                ]
            )
            ->add('value_date', 'date');
    }
}
