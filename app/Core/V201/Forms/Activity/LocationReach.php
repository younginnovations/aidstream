<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class LocationReach
 * @package App\Core\V201\Forms\Activity
 */
class LocationReach extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds location reach form
     */
    public function buildForm()
    {
        $this->addSelect('code', $this->getCodeList('GeographicLocationReach', 'Activity'), 'Code', $this->addHelpText('Activity_Location_LocationReach-code'));
    }
}
