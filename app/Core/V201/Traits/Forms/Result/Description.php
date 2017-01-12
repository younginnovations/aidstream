<?php namespace App\Core\V201\Traits\Forms\Result;

use App\Core\V201\Forms\Activity\Result as ResultForm;

/**
 * Class Description
 * @package App\Core\V201\Forms\Activity
 */
trait Description
{
    /**
     * @param array $data
     * @return ResultForm
     */
    public function addDescriptions(array $data = [])
    {
        return $this->addCollection('description', 'Activity\Title', '', $data, trans('elementForm.description'));
    }
}
