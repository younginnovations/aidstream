<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class RecipientCountry
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait RecipientCountry
{
    /**
     * add recipient country form
     * @return mixed
     */
    public function addRecipientCountry()
    {
        return $this->addCollection('recipient_country', 'Activity\Transactions\RecipientCountry', '', [], trans('elementForm.recipient_country'));
    }

    /**
     * get Country CodeList
     * @return mixed
     */
    public function getCountryCodeList()
    {
        return $this->getCodeList('Country', 'Organization');
    }
}
