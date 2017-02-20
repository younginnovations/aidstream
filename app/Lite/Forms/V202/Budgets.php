<?php namespace App\Lite\Forms\V202;

use App\Lite\Forms\FormPathProvider;
use App\Lite\Forms\LiteBaseForm;

/**
 * Class Budget
 * @package App\Lite\Forms\V202
 */
class Budgets extends LiteBaseForm
{

    use FormPathProvider;

    /**
     * Budget Form
     */
    public function buildForm()
    {
        $budgetFormPath = $this->getFormPath('Budget');

        return $this
            ->addToCollection('budget', ' ', $budgetFormPath, 'collection_form separator budget')
            ->addButton('add_more_budget', trans('lite/elementForm.add_another_budget'), 'budget', 'add_more');
    }
}
