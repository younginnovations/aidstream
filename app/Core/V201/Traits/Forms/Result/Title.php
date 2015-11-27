<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Title
 * @package App\Core\V201\Forms\Activity
 */
trait Title
{
    /**
     * Return title form
     * @return mixed
     */
    public function addTitles()
    {
        return $this->addCollection('title', 'Activity\Title');
    }
}
