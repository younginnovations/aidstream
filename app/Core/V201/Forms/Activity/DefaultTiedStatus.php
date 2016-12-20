<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class DefaultTiedStatus
 * @package App\Core\V201\Forms\Activity
 */
class DefaultTiedStatus extends BaseForm
{
    /**
     * builds the Activity Default Tied Status form
     */
    public function buildForm()
    {
        $this->addSelect('default_tied_status', $this->getCodeList('TiedStatus', 'Activity'), trans('elementForm.default_tied_status'), $this->addHelpText('Activity_DefaultTiedStatus-code'), null, true);
    }
}
