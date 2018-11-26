<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class Sector
 * @package App\Services\FormCreator\Activity
 */
class Sector
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
        $this->formPath    = $this->version->getActivityElement()->getSector()->getForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     * return activity sector edit form.
     */
    public function editForm($data, $activityId)
    {
        $model['sector'] = $data;
        // dd($this->formPath);
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.sector.update', [$activityId, 0])
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
