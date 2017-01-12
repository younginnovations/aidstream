<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PolicyMarker
 * @package App\Core\V201\Forms\Activity
 */
class PolicyMarker extends BaseForm
{

    public function buildForm()
    {
        $this
            ->addSelect('vocabulary', $this->getCodeList('PolicyMarkerVocabulary', 'Activity'), trans('elementForm.vocabulary'), $this->addHelpText('Activity_PolicyMarker-vocabulary'))
            ->addSelect('policy_marker', $this->getCodeList('PolicyMarker', 'Activity'), trans('elementForm.policy_marker'), $this->addHelpText('Activity_PolicyMarker-code'), null, true)
            ->addSelect('significance', $this->getCodeList('PolicySignificance', 'Activity'), trans('elementForm.significance'), $this->addHelpText('Activity_PolicyMarker-significance'), null, true)
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_policy_marker');
    }
}
