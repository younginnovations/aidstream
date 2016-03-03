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
        $getCode = new GetCodeName();
        $value   = $getCode->getCodeName($listType, $listName, $code);

        return ' [ ' . $value . ' ] ';
    }

    return "";
}
