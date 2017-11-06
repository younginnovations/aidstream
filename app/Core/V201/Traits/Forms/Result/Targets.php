<?php namespace App\Core\V201\Traits\Forms\Result;

use App\Core\V201\Forms\Activity\Result as ResultForm;

/**
 * Class Targets
 * @package App\Core\V201\Forms\Activity
 */
trait Targets
{
    /**
     * Return target form
     * @return ResultForm
     */
    public function addTargets()
    {
        return $this->addCollection('target', 'Activity\Target', '', ['class' => 'indicator_period_target_comment_title_narrative'], trans('elementForm.target'));
    }

    /**
     * Return actual target form
     * @return ResultForm
     */
    public function addActualTargets()
    {
        if (session()->get('version') === 'V201') {
            return $this->addCollection('actual', 'Activity\Target', '', ['class' => 'indicator_period_actual_comment_title_narrative'], trans('elementForm.actual'));
        } else {
            return $this->addCollection('actual', 'Activity\Actual', '', ['class' => 'indicator_period_actual_comment_title_narrative'], trans('elementForm.actual'));
        }
    }
}
