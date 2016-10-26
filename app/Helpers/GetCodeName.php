<?php namespace App\Helpers;

/**
 * Class GetCodeName
 * @package App\Helpers
 */
class GetCodeName
{

    /**
     * get activity code name
     * @param      $listName
     * @param      $code
     * @param bool $displayCode
     * @return mixed
     */
    public function getActivityCodeName($listName, $code, $displayCode = true)
    {
        return $this->getCodeName('Activity', $listName, $code, $displayCode);
    }

    /**
     * get organization code name
     * @param      $listName
     * @param      $code
     * @param bool $displayCode
     * @return mixed
     */
    public function getOrganizationCodeName($listName, $code, $displayCode = true)
    {
        return $this->getCodeName('Organization', $listName, $code, $displayCode);
    }

    /**
     * get the corresponding code name
     * @param      $listType
     * @param      $listName
     * @param      $code
     * @param bool $displayCode
     * @return mixed
     */
    public function getCodeName($listType, $listName, $code, $displayCode = true)
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
                return $displayCode ? sprintf('%s [%s]', $list['name'], $list['code']) : $list['name'];
            }
        }
    }

    /**
     * Get Only the activity code name (without the code value)
     * @param     $listName
     * @param     $code
     * @param int $default
     * @return string
     */
    public function getCodeNameOnly($listName, $code, $default = - 4)
    {
        $descriptionWithCode = $this->getCodeName('Activity', $listName, $code);

        return sprintf("%s", substr($descriptionWithCode, 0, $default));
    }

    /**
     * @param $listType
     * @param $listName
     * @param $code
     * @return string
     */
    public function getCode($listType, $listName, $code)
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
                return sprintf('[%s]', $list['code']);
            }
        }
    }
}
