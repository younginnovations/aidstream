<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class Financial
 * @package App\Core\V201\Forms\Settings
 */
class Financial extends BaseForm
{
    /**
     * build financial form
     */
    public function buildForm()
    {
        $this
            ->addCheckBox('budget', 'Budget', true, 'readonly')
            ->addCheckBox('planned_disbursement', 'Planned Disbursement')
            ->addCheckBox('transaction', 'Transaction', true, 'readonly')
            ->addCheckBox('capital_spend', 'Capital Spend');
    }
}
