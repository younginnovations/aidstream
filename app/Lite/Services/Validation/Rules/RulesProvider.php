<?php namespace App\Lite\Services\Validation\Rules;


/**
 * Class RulesProvider
 * @package App\Lite\Services\Validation\Rules
 */
class RulesProvider
{
    /**
     *
     */
    const BASE_NAMESPACE = 'App\Lite\Services\Validation\Rules\%s\%s';

    /**
     * @param $version
     * @param $entity
     * @return mixed
     */
    public function getRules($version, $entity)
    {
        return $this->getClass($version, $entity)->rules();
    }

    /**
     * @param $version
     * @param $entity
     * @return mixed
     */
    public function getMessages($version, $entity)
    {
        return $this->getClass($version, $entity)->messages();
    }

    /**
     * @param $version
     * @param $entity
     * @return mixed
     */
    protected function getClass($version, $entity)
    {
        return app()->make(sprintf(self::BASE_NAMESPACE, $version, $entity));
    }
}
