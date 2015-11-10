<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class DefaultTiedStatus
 * @package App\Services\FormCreator\Activity
 */
class DefaultTiedStatus
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
        $this->formPath    = $version->getActivityElement()->getDefaultTiedStatus()->getForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     * return Activity Default Tied Status edit form.
     */
    public function editForm($data, $activityId)
    {
        $model['default_tied_status'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.default-tied-status.update', [$activityId, 0])
            ]
        )->add('Save', 'submit');
    }
}
