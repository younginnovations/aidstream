<?php namespace App\Core\V203\Element\Activity;

use App\Models\Activity\Activity;

/**
 * Class DefaultAidType
 * @package app\Core\V201\Element\Activity
 */
class DefaultAidType
{
    /**
     * @return default aid type form path
     */
    public function getForm()
    {
        return 'App\Core\V203\Forms\Activity\DefaultAidType';
    }

    /**
     * @return default aid type repository
     */
    public function getRepository()
    {
        return App('App\Core\V203\Repositories\Activity\DefaultAidType');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [];
        $aidTypeArray = $activity->default_aid_type;
        if(empty($aidTypeArray)){
            return;
        }
        if(!is_array($aidTypeArray)) {
            $data = [
                'default_aid_type' => $aidTypeArray,
                'default_aidtype_vocabulary' => '1',
                'earmarking_category' => '',
                'default_aid_type_text' => '',
                'cash_and_voucher_modalities' => ''
            ];
            $aidTypeArray = [$data];
        }

        foreach($aidTypeArray as $aidType){

            $vocabulary = $aidType['default_aidtype_vocabulary'];
            if($vocabulary == 1){
                $code = $aidType['default_aid_type'];
            } else if ($vocabulary == 2) {
                $code = $aidType['earmarking_category'];
            } else if ($vocabulary == 3) {
                $code = $aidType['default_aid_type_text'];
            } else if($vocabulary == 4){
                $code = getVal($aidType, ['cash_and_voucher_modalities']);
            }
            $activityData[] = [
                '@attributes' => [
                    'code' => $code,
                    'vocabulary' => $vocabulary
                ]
            ];
        }

        return $activityData;
    }
}
