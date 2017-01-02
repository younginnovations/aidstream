<?php namespace App\Lite\Forms\V202;

use App\Lite\Forms\FormPathProvider;
use App\Lite\Forms\LiteBaseForm;

/**
 * Class IncomingFunds
 * @package App\Lite\Forms\V202
 */
class IncomingFunds extends LiteBaseForm
{

    use FormPathProvider;

    /**
     * Transaction Form
     */
    public function buildForm()
    {
        $formPath = $this->getFormPath('Transaction');

        return $this
            ->addToCollection('incomingfunds', ' ', $formPath, 'collection_form separator transaction')
            ->addButton('add_more_transaction', trans('lite/elementForm.add_another_incoming_funds'), 'transaction', 'add_more');
    }
}
