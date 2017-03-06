<?php namespace App\Lite\Forms;

/**
 * Class FormPathProvider
 * @package App\Lite\Forms
 */
trait FormPathProvider
{

    protected $allowedCustomizationVersion = [3 => ['Location', 'Point', 'Administrative']];

    protected $systemVersionToFolderMapping = [3 => 'Tz'];

    /**
     * Returns formPath of the provided file.
     *
     * @param      $file
     * @return string
     */
    public function getFormPath($file)
    {
        $version  = ($ver = (session('version'))) ? $ver : 'V202';
        $formPath = ($this->isAllowedCustomization($file)) ? $this->getPath($file, $version, $this->systemVersionToFolderMapping[$this->getSystemVersionId()]) : $this->getPath($file, $version);

        if (class_exists($formPath)) {
            return $formPath;
        }

        return $this->getPath($file, 'V202');
    }

    /**
     * Returns path of the file with specific version.
     *
     * @param      $file
     * @param      $version
     * @param null $systemVersion
     * @return string
     */
    public function getPath($file, $version, $systemVersion = null)
    {
        if ($systemVersion) {
            return sprintf('App\Lite\Forms\%s\%s\%s', $version, $systemVersion, $file);
        }

        return sprintf('App\Lite\Forms\%s\%s', $version, $file);
    }

    protected function isAllowedCustomization($file)
    {
        $systemVersionId = $this->getSystemVersionId();
        if ($systemVersionId) {
            if (array_key_exists($systemVersionId, $this->allowedCustomizationVersion)) {
                foreach (getVal($this->allowedCustomizationVersion, [$systemVersionId], []) as $index => $class) {
                    if ($class === $file) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    protected function getSystemVersionId()
    {
        if (auth()->check()) {
            if ($systemVersionId = auth()->user()->organization->system_version_id) {
                return $systemVersionId;
            }
        }

        return null;
    }
}

