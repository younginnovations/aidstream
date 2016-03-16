<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class CapitalSpend
 * @package App\Core\V201\Forms\Activity
 */
class CapitalSpend extends BaseForm
{
    /**
     * builds the Activity Capital Spend form
     */
    public function buildForm()
    {
        $this->add('capital_spend', 'text', ['help_block' => $this->addHelpText('Activity_CapitalSpend-percentage'), 'required' => true]);
    }
}
