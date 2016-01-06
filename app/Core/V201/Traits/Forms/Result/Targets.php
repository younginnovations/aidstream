<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Targets
 * @package App\Core\V201\Forms\Activity
 */
trait Targets
{
    /**
     * Return target form
     * @param array $data
     * @return mixed
     */
    public function addTargets($data = [])
    {
        $data ?: ['class' => 'narrative'];

        return $this
            ->addData($data)
            ->addCollection('target', 'Activity\Target');
    }

    /**
     * Return actual target form
     * @param array $data
     * @return mixed
     */
    public function addActualTargets($data = [])
    {
        $data ?: ['class' => 'narrative'];

        return $this
            ->addData($data)
            ->addCollection('actual', 'Activity\Target');
    }
}
