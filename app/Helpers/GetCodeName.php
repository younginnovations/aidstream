<?php namespace App\Helpers;

/**
 * Class GetCodeName
 * @package App\Helpers
 */
class GetCodeName
{

    /**
     * get activity code name
     * @param $listName
     * @param $code
     * @return mixed
     */
    public function getActivityCodeName($listName, $code)
    {
        return $this->getCodeName('Activity', $listName, $code);
    }

    /**
     * get organization code name
     * @param $listName
     * @param $code
     * @return mixed
     */
    public function getOrganizationCodeName($listName, $code)
    {
        return $this->getCodeName('Organization', $listName, $code);
    }

    /**
     * get the corresponding code name
     * @param $listType
     * @param $listName
     * @param $code
     * @return mixed
     */
    protected function  getCodeName($listType, $listName, $code)
    {
        $codeListFromFile = file_get_contents(app_path("Core/" . session()->get('version') . "/Codelist/" . config('app.locale') . "/$listType/$listName.json"));
        $codeLists        = json_decode($codeListFromFile, true);
        $codeList = $codeLists[$listName];
        
        foreach ($codeList as $list) {
            if ($list['code'] == $code) {
                return sprintf('%s [%s]', $list['name'], $list['code']);
            }
        }
    }
}
