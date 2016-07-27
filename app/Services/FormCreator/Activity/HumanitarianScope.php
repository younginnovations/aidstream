<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

/**
 * Class HumanitarianScope
 * @package App\Services\FormCreator\Activity
 */
class HumanitarianScope
{

    protected $formBuilder;
    protected $version;
    protected $formPath;

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = $version->getActivityElement()->getHumanitarianScopesForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     */
    public function editForm($data, $activityId)
    {
        $model['humanitarian_scope'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.humanitarian-scope.update', [$activityId, 0])
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
