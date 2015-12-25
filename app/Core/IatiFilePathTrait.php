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
     * return versioned file class
     * @param $name
     * @param $versionedDir
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function getClass($name, $versionedDir)
    {
        return app($this->getPath($name, $versionedDir));
    }

    /**
     * return versioned xml
     * @param $name
     * @param $versionedDir
     * @param $parameters
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function getXml($name, $versionedDir, $parameters)
    {
        return $this->getClass($name, $versionedDir)->getXmlData($parameters[0]);
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
     * handle method calls dynamically starting with get
     * @param $method
     * @param $parameters
     * @return \Illuminate\Foundation\Application|mixed
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'get')) {
            $pathInfo      = $this->getFileInfo($method);
            $fileType      = $pathInfo[0];
            $versionedDir  = $pathInfo[1][0];
            $methodType    = $pathInfo[1][1];
            $methodPattern = sprintf('/get([a-z]+)%s/i', $fileType);
            $name          = preg_replace($methodPattern, '$1', $method);
            $methodCall    = 'get' . $methodType;

            return $this->$methodCall($name, $versionedDir, $parameters);
        }
        throw new BadMethodCallException();
    }
}
