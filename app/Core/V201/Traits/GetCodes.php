<?php namespace App\Core\V201\Traits;

trait GetCodes
{
    /**
     * return code array from json codeList
     * @param $listName
     * @param $listType
     * @return array
     */
    public function getCodes($listName, $listType)
    {
        $codeListFromFile = file_get_contents(app_path("Core/" . session()->get('version') . "/Codelist/" . config('app.locale') . "/$listType/$listName.json"));
        $codeLists        = json_decode($codeListFromFile, true);
        $codeList         = $codeLists[$listName];
        $data             = [];

        foreach ($codeList as $list) {
            $data[] = $list['code'];
        }

        return $data;
    }
}
