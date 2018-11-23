<?php namespace App\Np\Forms\V202;

use App\Np\Forms\FormPathProvider;
use App\Np\Forms\NpBaseForm;

/**
 * Class IncomingFunds
 * @package App\Np\Forms\V202
 */
class IncomingFunds extends NpBaseForm
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
