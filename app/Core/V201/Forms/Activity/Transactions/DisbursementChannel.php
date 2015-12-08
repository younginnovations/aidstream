<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\DisbursementChannel as DisbursementChannelCodeList;

/**
 * Class DisbursementChannel
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class DisbursementChannel extends BaseForm
{
    use DisbursementChannelCodeList;
    protected $showFieldErrors = true;

    /**
     * builds Transaction Disbursement Channel form
     */
    public function buildForm()
    {
        $this
            ->add(
                'disbursement_channel_code',
                'select',
                [
                    'choices'     => $this->getDisbursementChannelCodeList(),
                    'empty_value' => 'Select one of the following option :',
                    'attr'        => ['class' => 'form-control disbursement_channel'],
                ]
            );
    }
}
