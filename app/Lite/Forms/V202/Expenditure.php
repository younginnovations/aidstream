<?php namespace App\Lite\Forms\V202;

use App\Lite\Forms\FormPathProvider;
use App\Lite\Forms\LiteBaseForm;

/**
 * Class Expenditure
 * @package App\Lite\Forms\V202
 */
class Expenditure extends LiteBaseForm
{

    use FormPathProvider;

    /**
     * Transaction Form
     */
    public function buildForm()
    {
        $formPath = $this->getFormPath('Transaction');

        return $this
            ->addToCollection('expenditure', ' ', $formPath, 'collection_form separator transaction')
            ->addButton('add_more_transaction', trans('lite/elementForm.add_another_expenditure'), 'transaction', 'add_more');
    }
}
