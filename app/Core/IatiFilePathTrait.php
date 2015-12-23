<?php namespace App\Core;

use BadMethodCallException;
use Illuminate\Support\Str;

/**
 * Class IatiFilePathTrait
 * @package App\Core
 */
trait IatiFilePathTrait
{

    private $type = '';

    /**
     * @param mixed $type
     */
    protected function setType($type)
    {
        $this->type = $type;
    }

    /**
     * return versioned file class
     * @param $name
     * @param $versionedDir
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function getClass($name, $versionedDir)
    {
        return app(sprintf('App\Core\V202\%s\%s\%s', $versionedDir, $this->type, $name));
    }

    /**
     * return versioned file path
     * @param $name
     * @param $versionedDir
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function getPath($name, $versionedDir)
    {
        return sprintf('App\Core\V202\%s\%s\%s', $versionedDir, $this->type, $name);
    }

    /**
     * return versioned xml
     * @param $name
     * @param $versionedDir
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function getXml($name, $versionedDir)
    {
        return $this->getClass($name, $versionedDir)->getXmlData();
    }

    /**
     * return versioned file path info
     * @param $method
     * @return array
     */
    protected function getFileInfo($method)
    {
        $versionedDirs = [
            'Form'       => ['Forms', 'Path'],
            'Element'    => ['Element', 'Class'],
            'Request'    => ['Requests', 'Class'],
            'Repository' => ['Repositories', 'Class'],
            'Xml'        => ['Xml', 'Xml']
        ];

        preg_match_all('/[A-Z][a-z]+/', $method, $matches);
        $fileType     = end($matches[0]);
        $versionedDir = $versionedDirs[$fileType];

        return [$fileType, $versionedDir];
    }

    /**
     * handel method calls dynamically starting with get
     * @param $method
     * @param $parameters
     * @return \Illuminate\Foundation\Application|mixed
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'get')) {
            $pathInfo   = $this->getFileInfo($method);
            $name       = str_replace(['get', $pathInfo[0]], '', $method);
            $methodCall = 'get' . $pathInfo[1][1];

            return $this->$methodCall($name, $pathInfo[1][0]);
        }
        throw new BadMethodCallException();
    }
}
