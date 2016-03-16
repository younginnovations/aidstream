<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Exactness
 * @package App\Core\V201\Forms\Activity
 */
class Exactness extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds exactness form
     */
    public function buildForm()
    {
        $this->addSelect('code', $this->getCodeList('GeographicExactness', 'Activity'), 'Code', $this->addHelpText('Activity_Location_Exactness-code'), null, true);
    }
}
