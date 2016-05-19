<?php namespace App\Core\V201\Traits\Forms\Result;

use App\Core\V201\Forms\Activity\Result as ResultForm;

/**
 * Class Baseline
 * @package App\Core\V201\Forms\Activity
 */
trait Baseline
{
    /**
     * Return baseline form
     * @return ResultForm
     */
    public function addBaselines()
    {
        return $this->addCollection('baseline', 'Activity\Baseline');
    }
}
