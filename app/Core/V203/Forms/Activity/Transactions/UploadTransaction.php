<?php namespace App\Core\V203\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class Type
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class UploadTransaction extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds Transaction upload form
     */
    public function buildForm()
    {
        $this
            ->add('transaction', 'file', ['label' => trans('elementForm.transaction_csv_file')]);
    }
}
