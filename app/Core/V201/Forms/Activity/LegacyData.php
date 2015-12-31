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
            ->add('name', 'text', ['help_block' => $this->addHelpText('Activity_LegacyData-name')])
            ->add('value', 'text', ['help_block' => $this->addHelpText('Activity_LegacyData-value')])
            ->add('iati_equivalent', 'text', ['help_block' => $this->addHelpText('Activity_LegacyData-iati_equivalent')])
            ->addRemoveThisButton('remove');
    }
}
