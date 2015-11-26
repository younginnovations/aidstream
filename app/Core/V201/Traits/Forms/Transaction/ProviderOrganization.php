<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class ProviderOrganization
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait ProviderOrganization
{
    /**
     * add provider organization form
     * @return mixed
     */
    public function addProviderOrganization()
    {
        return $this->addCollection('provider_organization', 'Activity\Transactions\ProviderOrganization');
    }
}
