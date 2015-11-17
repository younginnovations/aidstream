<?php namespace App\Core\V201\Traits\Forms\Result;

/**
 * Class Comment
 * @package App\Core\V201\Forms\Activity
 */
trait Comment
{
    /**
     * Return comment form
     * @return mixed
     */
    public function addComments()
    {
        return $this->addCollection('comment', 'Activity\Title');
    }
}
