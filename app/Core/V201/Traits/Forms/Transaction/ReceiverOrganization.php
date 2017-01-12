<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class ReceiverOrganization
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait ReceiverOrganization
{
    /**
     * add receiver organization form
     * @return mixed
     */
    public function addReceiverOrganization()
    {
        return $this->addCollection('receiver_organization', 'Activity\Transactions\ReceiverOrganization', '', [], trans('elementForm.receiver_organisation'));
    }
}
