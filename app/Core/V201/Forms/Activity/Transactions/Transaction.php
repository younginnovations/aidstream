<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\AidType;
use App\Core\V201\Traits\Forms\Transaction\Description;
use App\Core\V201\Traits\Forms\Transaction\DisbursementChannel;
use App\Core\V201\Traits\Forms\Transaction\FinanceType;
use App\Core\V201\Traits\Forms\Transaction\FlowType;
use App\Core\V201\Traits\Forms\Transaction\ProviderOrganization;
use App\Core\V201\Traits\Forms\Transaction\ReceiverOrganization;
use App\Core\V201\Traits\Forms\Transaction\RecipientCountry;
use App\Core\V201\Traits\Forms\Transaction\RecipientRegion;
use App\Core\V201\Traits\Forms\Transaction\Sector;
use App\Core\V201\Traits\Forms\Transaction\TiedStatus;
use App\Core\V201\Traits\Forms\Transaction\TransactionDate;
use App\Core\V201\Traits\Forms\Transaction\TransactionType;
use App\Core\V201\Traits\Forms\Transaction\Value;

/**
 * Class Transaction
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class Transaction extends BaseForm
{
    use TransactionType;
    use TransactionDate;
    use Value;
    use Description;
    use ProviderOrganization;
    use ReceiverOrganization;
    use DisbursementChannel;
    use Sector;
    use RecipientCountry;
    use RecipientRegion;
    use FlowType;
    use FinanceType;
    use AidType;
    use TiedStatus;
    protected $showFieldErrors = true;

    /**
     * builds Transaction
     */
    public function buildForm()
    {
        $this
            ->add('reference', 'text')
            ->addTransactionType()
            ->addTransactionDate()
            ->addValue()
            ->addDescription()
            ->addProviderOrganization()
            ->addReceiverOrganization()
            ->addDisbursementChannel()
            ->addSector()
            ->addRecipientCountry()
            ->addRecipientRegion()
            ->addFlowType()
            ->addFinanceType()
            ->addAidType()
            ->addTiedStatus();
    }
}
