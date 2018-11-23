<?php namespace App\Np\Forms\V202;


use App\Np\Forms\NpBaseForm;

/**
 * Class TransactionForm
 * @package App\Np\Forms\V202
 */
class Transaction extends NpBaseForm
{
    /**
     * Form structure for funding organisation and implementing organisation.
     */
    public function buildForm()
    {
        $currency = $this->getCodeList('Currency', 'Activity');

        $type = explode("[", $this->name);

        $organisation = trans('lite/elementForm.receiver_organisation');

        if ($type[0] == 'incomingfunds') {
            $organisation = trans('lite/elementForm.provider_organisation');
        }

        $this->add('id', 'hidden')
             ->add('reference', 'text', ['label' => trans('lite/elementForm.reference'), 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->add('date', 'date', ['label' => trans('lite/elementForm.date'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->add('amount', 'text', ['label' => trans('lite/elementForm.amount'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->addSelect(
                 'currency',
                 $currency,
                 trans('lite/elementForm.currency'),
                 null,
                 null,
                 false,
                 [
                     'wrapper' => ['class' => 'form-group col-sm-6']
                 ]
             )
             ->add('description', 'textarea', ['label' => trans('lite/elementForm.description'), 'wrapper' => ['class' => 'form-group col-sm-6 form__description']])
             ->add('organisation', 'text', ['label' => $organisation, 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->addRemoveThisButton('remove-transaction');
    }
}
