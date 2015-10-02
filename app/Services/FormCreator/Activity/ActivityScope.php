<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class ActivityScope
 * @package App\Services\FormCreator\Activity
 */
class ActivityScope
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
        $this->version     = $version;
        $this->formPath    = $this->version->getActivityElement()->getActivityScope()->getForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     * return activity scope edit form.
     */
    public function editForm($data, $activityId)
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $data,
                'url'    => route('activity.activity-scope.update', [$activityId, 0])
            ]
        )->add('Save', 'submit');
    }
}
