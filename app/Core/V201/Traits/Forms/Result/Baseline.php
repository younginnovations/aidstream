<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Baseline
 * @package App\Core\V201\Forms\Activity
 */
trait Baseline
{
    /**
     * Return baseline form
     * @param array $data
     * @return mixed
     */
    public function addBaselines($data = [])
    {
        $data ?: ['class' => 'narrative'];

        return $this
            ->addData($data)
            ->addCollection('baseline', 'Activity\Baseline');
    }
}
