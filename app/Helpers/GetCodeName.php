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
    public function  getCodeName($listType, $listName, $code)
    {
        $defaultVersion = config('app.default_version_name');
        $defaultLocale  = config('app.fallback_locale');
        $version        = session()->get('version');
        $locale         = config('app.locale');
        $rawFilePath    = app_path("Core/%s/Codelist/%s/$listType/$listName.json");
        $filePath       = sprintf($rawFilePath, $version, $locale);
        file_exists($filePath) ?: $filePath = sprintf($rawFilePath, $version, $defaultLocale);
        file_exists($filePath) ?: $filePath = sprintf($rawFilePath, $defaultVersion, $locale);
        file_exists($filePath) ?: $filePath = sprintf($rawFilePath, $defaultVersion, $defaultLocale);
        $codeListFromFile = file_get_contents($filePath);
        $codeLists        = json_decode($codeListFromFile, true);
        $codeList         = $codeLists[$listName];

        foreach ($codeList as $list) {
            if ($list['code'] == $code) {
                return sprintf('%s [%s]', $list['name'], $list['code']);
            }
        }
    }
}
