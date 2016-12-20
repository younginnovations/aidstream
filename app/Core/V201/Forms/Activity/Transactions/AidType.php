<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\AidType as AidTypeCodeList;

/**
 * Class AidType
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class AidType extends BaseForm
{
    use AidTypeCodeList;
    protected $showFieldErrors = true;

    /**
     * builds aid type form
     */
    public function buildForm()
    {
        $this->addSelect('aid_type', $this->getAidTypeCodeList(), trans('elementForm.aid_type'), $this->addHelpText('Activity_Transaction_AidType-code'));
    }
}
