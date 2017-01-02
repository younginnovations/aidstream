<?php namespace App\Core\V201\Traits;

trait GetCodes
{
    /**
     * return code array from json codeList
     * @param        $listName
     * @param        $listType
     * @param string $type
     * @return array
     */
    public function getCodes($listName, $listType, $type = 'code')
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
        $data             = [];

        foreach ($codeList as $list) {
            $data[] = $list[$type];
        }

        return $data;
    }

    public function getStringFormatCode($listName, $listType, $type = 'code')
    {
        $code = $this->getCodes($listName, $listType, $type);

        return implode(",", $code);
    }

    /**
     * Returns the given listName with code as a key and name as value.
     *
     * @param $listType
     * @param $listName
     * @return array
     */
    public function getNameWithCode($listType, $listName)
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
        $data             = [];

        foreach ($codeList as $list) {
            $data[$list['code']] = $list['name'];
        }

        return $data;
    }
}

