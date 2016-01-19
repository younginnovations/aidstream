<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class PolicyMaker
 * @package App\Services\FormCreator\Activity
 */
class PolicyMaker
{

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->version     = $version;
        $this->formPath    = $this->version->getActivityElement()->getPolicyMaker()->getForm();
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
                'url'    => route('activity.policy-maker.update', [$activityId, 0])
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
