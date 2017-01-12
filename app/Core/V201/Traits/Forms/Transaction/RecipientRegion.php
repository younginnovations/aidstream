<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class RecipientRegion
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait RecipientRegion
{
    /**
     * add recipient region form
     * @return mixed
     */
    public function addRecipientRegion()
    {
        return $this->addCollection('recipient_region', 'Activity\Transactions\RecipientRegion', '', [], trans('elementForm.recipient_region'));
    }

    /**
     * get Recipient Region CodeList
     * @return mixed
     */
    public function getRegionCodeList()
    {
        return $this->getCodeList('Region', 'Activity');
    }

    /**
     * get RegionVocabulary CodeList
     * @return mixed
     */
    public function getRegionVocabularyCodeList()
    {
        return $this->getCodeList('RegionVocabulary', 'Activity');
    }
}
