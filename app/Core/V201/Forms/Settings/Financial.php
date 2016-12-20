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
            ->addCheckBox('budget', trans('element.budget'), true, 'readonly')
            ->addCheckBox('planned_disbursement', trans('element.planned_disbursement'))
            ->addCheckBox('transaction', trans('element.transaction'), true, 'readonly')
            ->addCheckBox('capital_spend', trans('element.capital_spend'));
    }
}
