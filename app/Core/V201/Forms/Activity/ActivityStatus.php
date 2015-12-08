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
        $this
            ->add(
                'activity_status',
                'select',
                [
                    'choices'     => $this->getCodeList('ActivityStatus', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Activity status'
                ]
            );
    }
}
