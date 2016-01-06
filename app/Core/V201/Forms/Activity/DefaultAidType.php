<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class DefaultAidType
 * @package App\Core\V201\Forms\Activity
 */
class DefaultAidType extends BaseForm
{
    /**
     * builds the Activity Default Aid Type form
     */
    public function buildForm()
    {
        $this->addSelect('default_aid_type', $this->getCodeList('AidType', 'Activity'), 'Default Aid Type', $this->addHelpText('Activity_DefaultAidType-code'));
    }
}
