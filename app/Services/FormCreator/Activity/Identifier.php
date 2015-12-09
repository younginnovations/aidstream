<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

class Identifier
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
        $this->formPath    = $this->version->getActivityElement()->getIdentifier()->getForm();
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
                'url'    => route('activity.store')
            ]
        )->add('Create Activity', 'submit', ['attr' => ['class' => 'btn btn-primary btn-create']]);
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
                'url'    => route('activity.iati-identifier.update', [$activityId, 0])
            ]
        )->add('id', 'hidden', ['value' => $activityId])->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
