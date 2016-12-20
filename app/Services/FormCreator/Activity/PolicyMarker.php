<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class PolicyMarker
 * @package App\Services\FormCreator\Activity
 */
class PolicyMarker
{

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->version     = $version;
        $this->formPath    = $this->version->getActivityElement()->getPolicyMarker()->getForm();
    }

    /**
     * @param $data
     * @param $activityId
     * @return $this
     */
    public function editForm($data, $activityId)
    {
        $model['policy_marker'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.policy-marker.update', [$activityId, 0])
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form'],'label' => trans('global.save')])
            ->add('Cancel', 'static', [
                'tag'     => 'a',
                'label' => false,
                'value' => trans('global.cancel'),
                'attr'    => [
                    'class' => 'btn btn-cancel',
                    'href'  => route('activity.show', $activityId)
                ],
                'wrapper' => false
            ]);
    }
}
