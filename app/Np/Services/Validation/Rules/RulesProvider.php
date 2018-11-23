<?php namespace App\Np\Services\Validation\Rules;


/**
 * Class RulesProvider
 * @package App\Np\Services\Validation\Rules
 */
class RulesProvider
{
    /**
     *
     */
    const BASE_NAMESPACE = 'App\Np\Services\Validation\Rules\%s\%s';

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
