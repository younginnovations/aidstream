<?php namespace App\Core\V203\Traits\Forms\Activity;

/**
 * Class AidType
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait AidType
{
    /**
     * add aid type form
     * @return mixed
     */
    public function addAidType()
    {
        return $this->addCollection('aid_type', 'Activity\DefaultAidType', '', [], trans('elementForm.aid_type'));
    }

    /**
     * get AidType CodeList
     * @return mixed
     */
    public function getAidTypeCodeList()
    {
        return $this->getCodeList('AidType', 'Activity');
    }
}
