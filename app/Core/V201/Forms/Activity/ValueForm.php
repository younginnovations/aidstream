<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ValueForm
 * @package App\Core\V201\Forms\Activity
 */
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
                    'choices' => $this->getCodeList('Currency', 'Activity'),
                    'label'   => 'Currency'
                ]
            )
            ->add('value_date', 'date');
    }
}
