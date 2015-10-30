<?php namespace App\Services\Wizard\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

/**
 * Class IatiIdentifier
 * @package app\Services\Wizard\FormCreator\Activity
 */
class IatiIdentifier
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
        $this->version     = $version;
        $this->formPath    = $this->version->getActivityElement()->getWizardIatiIdentifier()->getForm();
    }

    /**
     * @return $this
     */
    public function create()
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'POST',
                'url'    => route('wizard.activity.store')
            ]
        )->add('Step 2 >>', 'submit');
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
                'url'    => route('wizard.activity.iati-identifier.update', [$activityId, 0])
            ]
        )->add('Save', 'submit');
    }
}
