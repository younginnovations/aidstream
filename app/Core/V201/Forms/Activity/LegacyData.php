<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class LegacyData
 * @package App\Core\V201\Forms\Activity
 */
class LegacyData extends BaseForm
{
    /**
     * builds activity activity date form
     */
    public function buildForm()
    {
        $this
            ->add('name', 'text', ['label' => trans('elementForm.name'), 'help_block' => $this->addHelpText('Activity_LegacyData-name'), 'required' => true])
            ->add('value', 'text', ['label' => trans('elementForm.value'), 'help_block' => $this->addHelpText('Activity_LegacyData-value'), 'required' => true])
            ->add('iati_equivalent', 'text', ['label' => trans('elementForm.iati_equivalent'), 'help_block' => $this->addHelpText('Activity_LegacyData-iati_equivalent')])
            ->addRemoveThisButton('remove');
    }
}
