<?php namespace App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\V203;

use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\Activity as V202;
use Illuminate\Support\Facades\Log;

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
        $this->defaultAidType[$this->index]                                 = $template['default_aid_type'];
        $this->defaultAidType[$this->index]['default_aid_type']             = $this->attributes($element, 'code');
        $this->defaultAidType[$this->index]['default_aidtype_vocabulary']   = $this->attributes($element, 'vocabulary');
        $this->index ++;

        return $this->defaultAidType;
    }

}
