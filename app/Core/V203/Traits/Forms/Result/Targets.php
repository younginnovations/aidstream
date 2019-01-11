<?php namespace App\Core\V203\Traits\Forms\Result;

use App\Core\V203\Forms\Activity\Result as ResultForm;

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
        return $this->addCollection('target', 'Activity\Target', 'target', ['class' => 'indicator_period_target_comment_title_narrative'], trans('elementForm.target'))
                    ->addAddMoreButton('add_target', 'target');

    }

    /**
     * Return actual target form
     * @return ResultForm
     */
    public function addActualTargets()
    {
        return $this->addCollection('actual', 'Activity\Actual', 'actual', ['class' => 'indicator_period_actual_comment_title_narrative'], trans('elementForm.actual'))
                    ->addAddMoreButton('add_actual', 'actual');
    }
}
