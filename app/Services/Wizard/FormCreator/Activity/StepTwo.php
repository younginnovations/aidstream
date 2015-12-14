<?php namespace App\Services\Wizard\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

/**
 * Class StepTwo
 * @package App\Services\Wizard\FormCreator\Activity
 */
class StepTwo
{
    protected $formBuilder;
    protected $version;
    protected $formPath;

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    public function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = $version->getActivityElement()->getStepTwo()->getForm();
    }

    /**
     * @param $data
     * @param $activityId
     * @return edit form
     */
    public function editForm($data, $activityId)
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $data,
                'url'    => route('wizard.activity.title-description.update', [$activityId, 0])
            ]
        )->add('Step 3 >>', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
