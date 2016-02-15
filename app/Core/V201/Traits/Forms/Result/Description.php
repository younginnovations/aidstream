<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Description
 * @package App\Core\V201\Forms\Activity
 */
trait Description
{
    /**
     * @param array $data
     * @return mixed
     */
    public function addDescriptions($data = [])
    {
        return $this
            ->addData($data)
            ->addCollection('description', 'Activity\Title', 'description');
    }
}
