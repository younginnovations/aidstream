<?php namespace App\Core\V203\Traits\Forms;

/**
 * Class Sector
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait Tag
{
    // /**
    //  * add sector form
    //  * @return mixed
    //  */
    // public function addSector()
    // {
    //     return $this->addCollection('sector', 'Activity\Transactions\Sector', 'sector')
    //                 ->addAddMoreButton('add', 'sector');
    // }

    /**
     * get SectorVocabulary CodeList
     * @return mixed
     */
    public function getTagVocabularyCodeList()
    {
        return $this->getCodeList('TagVocabulary', 'Activity');
    }

    // /**
    //  * get Sector CodeList
    //  * @return mixed
    //  */
    // public function getTagCodeList()
    // {
    //     return $this->getCodeList('Tag', 'Activity');
    // }

    // /**
    //  * get Sector Category CodeList
    //  * @return mixed
    //  */
    // public function getTagCategoryCodeList()
    // {
    //     return $this->getCodeList('TagCategory', 'Activity');
    // }
}
