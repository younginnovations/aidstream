<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;


/**
 * Class Budget
 * @package App\Services\FormCreator\Activity
 */
class Budget
{

    /**
     * @var FormBuilder
     */
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
        $this->version     = $version;
        $this->formPath    = $this->version->getActivityElement()->getBudget()->getForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     * return activity activity date edit form.
     */
    public function editForm($data, $activityId)
    {
        $model['budget'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.budget.update', [$activityId, 0])
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
