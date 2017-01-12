<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class Sector
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait Sector
{
    /**
     * add sector form
     * @return mixed
     */
    public function addSector()
    {
        return $this->addCollection('sector', 'Activity\Transactions\Sector', '', [], trans('elementForm.sector'));
    }

    /**
     * get SectorVocabulary CodeList
     * @return mixed
     */
    public function getSectorVocabularyCodeList()
    {
        return $this->getCodeList('SectorVocabulary', 'Activity');
    }

    /**
     * get Sector CodeList
     * @return mixed
     */
    public function getSectorCodeList()
    {
        return $this->getCodeList('Sector', 'Activity');
    }

    /**
     * get Sector Category CodeList
     * @return mixed
     */
    public function getSectorCategoryCodeList()
    {
        return $this->getCodeList('SectorCategory', 'Activity');
    }
}
