<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Title
 * @package App\Core\V201\Forms\Activity
 */
trait Title
{
    /**
     * Return title form
     * @param array $data
     * @return mixed
     */
    public function addTitles($data = [])
    {
        $data ?: ['class' => 'narrative'];

        return $this
            ->addData($data)
            ->addCollection('title', 'Activity\Title');
    }
}
