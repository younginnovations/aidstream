<?php namespace App\Core\V201\Traits\Forms\Result;

use App\Core\V201\Forms\Activity\Result as ResultForm;

/**
 * Class Title
 * @package App\Core\V201\Forms\Activity
 */
trait Title
{
    /**
     * Return title form
     * @param array $data
     * @return ResultForm
     */
    public function addTitles(array $data = [])
    {
        return $this->addCollection('title', 'Activity\Title', '', $data, trans('elementForm.title'));
    }
}
