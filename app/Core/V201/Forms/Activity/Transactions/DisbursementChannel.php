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
        $this->addSelect(
            'disbursement_channel_code',
            $this->getDisbursementChannelCodeList(),
            'Disbursement Channel Code',
            $this->addHelpText('Activity_Transaction_DisbursementChannel-code'),
            ['attr' => ['class' => 'form-control disbursement_channel']]
        );
    }
}
