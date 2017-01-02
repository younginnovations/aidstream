<?php namespace App\Lite\Forms;

/**
 * Class FormPathProvider
 * @package App\Lite\Forms
 */
trait FormPathProvider
{
    /**
     * Returns formPath of the provided file.
     *
     * @param $file
     * @return string
     */
    public function getFormPath($file)
    {
        $version  = ($ver = (session('version'))) ? $ver : 'V202';
        $formPath = $this->getPath($file, $version);

        if (class_exists($formPath)) {
            return $formPath;
        }

        return $this->getPath($file, 'V202');
    }

    /**
     * Returns path of the file with specific version.
     *
     * @param $file
     * @param $version
     * @return string
     */
    public function getPath($file, $version)
    {
        return sprintf('App\Lite\Forms\%s\%s', $version, $file);
    }

}
