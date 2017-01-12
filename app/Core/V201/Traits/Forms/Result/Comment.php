<?php namespace App\Core\V201\Traits\Forms\Result;

use App\Core\V201\Forms\Activity\Result as ResultForm;

/**
 * Class Comment
 * @package App\Core\V201\Forms\Activity
 */
trait Comment
{
    /**
     * Return comment form
     * @param array $data
     * @return ResultForm
     */
    public function addComments(array $data = [])
    {
        return $this->addCollection('comment', 'Activity\Title', '', $data, trans('elementForm.comment'));
    }
}
