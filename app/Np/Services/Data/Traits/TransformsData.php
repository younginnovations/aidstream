<?php namespace App\Np\Services\Data\Traits;

use App\Np\Services\Data\Contract\MapperInterface;


/**
 * Class Transformer
 * @package App\Np\Services\Data\Traits
 */
trait TransformsData
{
    protected $baseNamespace = 'App\Np\Services\Data\%s\%s\%s';

    /**
     * Transform data into a format compatible with the database.
     *
     * @param MapperInterface $mapper
     * @return array
     */
    protected function transform(MapperInterface $mapper)
    {
        return $mapper->map();
    }

    /**
     * Transform database data into a frontend compatible format.
     *
     * @param MapperInterface $mapper
     * @return array
     */
    protected function transformReverse(MapperInterface $mapper)
    {
        return $mapper->reverseMap();
    }

    /**-
     * Get the object mapping for the data for different entities.
     *
     * @param array $rawData
     * @param       $className
     * @param       $version
     * @return mixed
     */
    protected function getMapping(array $rawData, $className, $version)
    {
        $classNameSpace = sprintf($this->baseNamespace, $version, $className, $className);

        return app()->make($classNameSpace, [$rawData]);
    }
}
