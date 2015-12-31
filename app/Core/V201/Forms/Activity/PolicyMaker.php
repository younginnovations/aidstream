<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PolicyMaker
 * @package App\Core\V201\Forms\Activity
 */
class PolicyMaker extends BaseForm
{

    public function buildForm()
    {
        $this
            ->addSelect('vocabulary', $this->getCodeList('PolicyMarkerVocabulary', 'Activity'), 'Vocabulary', $this->addHelpText('Activity_PolicyMarker-vocabulary'))
            ->addSelect('policy_marker', $this->getCodeList('PolicyMarker', 'Activity'), 'Policy Marker', $this->addHelpText('Activity_PolicyMarker-code'))
            ->addSelect('significance', $this->getCodeList('PolicySignificance', 'Activity'), 'Significance', $this->addHelpText('Activity_PolicyMarker-significance'))
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_policy_maker');
    }
}
