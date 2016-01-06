<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class LocationClass
 * @package App\Core\V201\Forms\Activity
 */
class LocationClass extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds location class form
     */
    public function buildForm()
    {
        $this->addSelect('code', $this->getCodeList('GeographicLocationClass', 'Activity'), 'Code', $this->addHelpText('Activity_Location_LocationClass-code'));

    }
}
