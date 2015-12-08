<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class RelatedActivity
 * @package App\Services\FormCreator\Activity
 */
class RelatedActivity
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
        $this->formPath    = $this->version->getActivityElement()->getRelatedActivity()->getForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     * return activity activity date edit form.
     */
    public function editForm($data, $activityId)
    {
        $model['related_activity'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.related-activity.update', [$activityId, 0])
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
