<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class DefaultAidType
 * @package App\Services\FormCreator\Activity
 */
class DefaultAidType
{
    /**
     * @var FormBuilder
     */
    protected $formBuilder;
    /**
     * @var Version
     */
    protected $version;
    /**
     * @var
     */
    protected $formPath;

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = $version->getActivityElement()->getDefaultAidType()->getForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     * return Activity Default Aid Type edit form.
     */
    public function editForm($data, $activityId)
    {
        $model['default_aid_type'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.default-aid-type.update', [$activityId, 0])
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']])
            ->add('Cancel', 'static', [
                'tag'     => 'a',
                'label'   => false,
                'value'   => 'Cancel',
                'attr'    => [
                    'class' => 'btn btn-cancel',
                    'href'  => route('activity.show', $activityId)
                ],
                'wrapper' => false
            ]);   
    }
}
