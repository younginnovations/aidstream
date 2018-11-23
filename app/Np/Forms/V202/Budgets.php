<?php namespace App\Np\Forms\V202;

use App\Np\Forms\FormPathProvider;
use App\Np\Forms\NpBaseForm;

/**
 * Class Budget
 * @package App\Np\Forms\V202
 */
class Budgets extends NpBaseForm
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
