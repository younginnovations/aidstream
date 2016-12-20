<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class ParticipatingOrganization
 * @package App\Services\FormCreator\Activity
 */
class ParticipatingOrganization
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
        $this->version     = $version;
        $this->formPath    = $this->version->getActivityElement()->getParticipatingOrganization()->getForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     * return activity Participating Organization edit form.
     */
    public function editForm($data, $activityId)
    {
        $model['participating_organization'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.participating-organization.update', [$activityId, 0])
            ]
        )
                                 ->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form'],'label' => trans('global.save')])
                                 ->add(
                                     'Cancel',
                                     'static',
                                     [
                                         'tag'     => 'a',
                                         'label' => false,
                                         'value' => trans('global.cancel'),
                                         'attr'    => [
                                             'class' => 'btn btn-cancel',
                                             'href'  => route('activity.show', $activityId)
                                         ],
                                         'wrapper' => false
                                     ]
                                 );
    }
}
