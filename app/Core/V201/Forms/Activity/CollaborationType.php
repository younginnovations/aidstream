<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class CollaborationType
 * @package App\Core\V201\Forms\Activity
 */
class CollaborationType extends BaseForm
{
    /**
     * builds the Activity Collaboration Type form
     */
    public function buildForm()
    {
        $this->addSelect('collaboration_type', $this->getCodeList('CollaborationType', 'Activity'), trans('elementForm.collaboration_type'), $this->addHelpText('Activity_CollaborationType-code'), null, true);
    }
}
