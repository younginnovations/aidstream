<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Budgets
 * @package App\Core\V201\Forms\Activity
 */
class Budgets extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('budget', 'Activity\Budget', 'budget')
            ->addAddMoreButton('add_budget', 'budget');
    }
}
