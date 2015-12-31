<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\FinanceType as FinanceTypeCodeList;

/**
 * Class FinanceType
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class FinanceType extends BaseForm
{
    use FinanceTypeCodeList;
    protected $showFieldErrors = true;

    /**
     * builds finance type form
     */
    public function buildForm()
    {
        $this->addSelect('finance_type', $this->getFinanceTypeCodeList(), 'Finance Type', $this->addHelpText('Activity_Transaction_FinanceType-code'));
    }
}
