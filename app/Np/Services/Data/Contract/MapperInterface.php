<?php namespace App\Np\Services\Data\Contract;

/**
 * Interface MapperInterface
 * @package App\Np\Services\Data\Contract
 */
interface MapperInterface
{
    /**
     * Map raw data into the database compatible format.
     *
     * @return array
     */
    public function map();

    /**
     * Map database data into frontend compatible format.
     *
     * @return array
     */
    public function reverseMap();
}
