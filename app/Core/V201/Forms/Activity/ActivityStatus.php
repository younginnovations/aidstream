<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ActivityStatus
 * @package App\Core\V201\Forms\Activity
 */
class ActivityStatus extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds activity status form
     */
    public function buildForm()
    {
        $this->addSelect('activity_status', $this->getCodeList('ActivityStatus', 'Activity'), trans('elementForm.activity_status'), $this->addHelpText('Activity_ActivityStatus-code'), null, true);
    }
}
