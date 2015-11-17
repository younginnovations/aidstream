<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Period as PeriodTrait;
use App\Core\V201\Traits\Forms\Result\Targets;

/**
 * Class Period
 * Contains the function to create the period form
 * @package App\Core\V201\Forms\Activity
 */
class Period extends BaseForm
{
    use PeriodTrait, Targets;

    /**
     * builds the activity period form
     */
    public function buildForm()
    {
        $this
            ->addPeriodStart()
            ->addPeriodEnd()
            ->addTargets()
            ->addActualTargets();
    }
}
