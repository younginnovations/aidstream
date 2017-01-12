<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\TiedStatus as TiedStatusCodeList;

/**
 * Class TiedStatus
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class TiedStatus extends BaseForm
{
    use TiedStatusCodeList;
    protected $showFieldErrors = true;

    /**
     * builds tied status code form
     */
    public function buildForm()
    {
        $this->addSelect('tied_status_code', $this->getTiedStatusCodeList(), trans('elementForm.tied_status_code'), $this->addHelpText('Activity_Transaction_TiedStatus-code'));
    }
}
