<?php namespace App\Core\V201\Forms\Activity;

/**
 * Class CodeList
 * @package App\Core\V201\Forms\Activity
 */
class CodeList
{
    /**
     * return codeList array from json codeList
     * @param $codeListName
     * @return array
     */
    public function getCodeList($codeListName)
    {
        $codeListContent = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/$codeListName.json")
        );
        $codeListData    = json_decode($codeListContent, true);
        $codeList        = $codeListData[$codeListName];
        $data            = [];

        foreach ($codeList as $list) {
            $data[$list['code']] = $list['code'] . ' - ' . $list['name'];
        }

        return $data;
    }
}
