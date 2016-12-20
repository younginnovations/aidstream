<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PolicyMarker
 * @package App\Core\V202\Forms\Activity
 */
class PolicyMarker extends BaseForm
{

    public function buildForm()
    {
        $this
            ->addSelect('vocabulary', $this->getCodeList('PolicyMarkerVocabulary', 'Activity'), trans('elementForm.vocabulary'))
            ->add('vocabulary_uri', 'text', ['label' => trans('elementForm.vocabulary_uri')])
            ->addSelect('significance', $this->getCodeList('PolicySignificance', 'Activity'), trans('elementForm.significance'))
            ->addSelect('policy_marker', $this->getCodeList('PolicyMarker', 'Activity'), trans('elementForm.policy_marker'), null, null, true)
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_policy_marker');
    }
}
