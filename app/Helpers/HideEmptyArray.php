<?php

use App\Helpers\GetCodeName;

/**
 * @param $listType
 * @param $listName
 * @param $code
 * @return string
 */
function hideEmptyArray($listType, $listName, $code)
{
    if (!empty($code)) {
        return sprintf(' [%s]', $code);
        /*
         * Unwanted for now.
         */
//        $getCode = new GetCodeName();
//        $value   = $getCode->getCodeName($listType, $listName, $code);
    }

    return "";
}
