<?php namespace App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\V203;

use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\Activity as V202;

/**
 * Class Activity
 * @package App\Services\XmlImporter\Mapper\V103\Activity
 */
class Activity extends V202
{
    /**
     * @param $element
     * @param $template
     * @return mixed|string
     */
    public function defaultAidType($element, $template)
    {
        $this->defaultAidType[$this->index]                                     = $template['default_aid_type'];

        $vocabulary = $this->attributes($element, 'vocabulary');

        if($vocabulary == 1){
            $this->defaultAidType[$this->index]['default_aid_type']             = $this->attributes($element, 'code');
        } else if ($vocabulary == 2) {
            $this->defaultAidType[$this->index]['earmarking_category']          = $this->attributes($element, 'code');
        } else if ($vocabulary == 3) {
            $this->defaultAidType[$this->index]['default_aid_type_text']        = $this->attributes($element, 'code');
        } else if($vocabulary == 4){
            $this->defaultAidType[$this->index]['cash_and_voucher_modalities']  = $this->attributes($element, 'code');
        }

        $this->defaultAidType[$this->index]['default_aidtype_vocabulary']       = $vocabulary;
        $this->index ++;

        return $this->defaultAidType;
    }

}
