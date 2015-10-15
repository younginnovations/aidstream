<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class BudgetItemDescription
 * @package App\Core\V201\Forms\Activity
 */
class BudgetItemDescription extends BaseForm
{
    /**
     * builds activity country budget item description form
     */
    public function buildForm()
    {
        $this
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_description');
    }
}
