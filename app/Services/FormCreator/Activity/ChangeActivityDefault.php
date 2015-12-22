<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

class ChangeActivityDefault
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
        $this->formPath    = $this->version->getActivityElement()->getChangeActivityDefault()->getForm();
    }

    /**
     * @param $data
     * @param $orgId
     * @return edit form
     */
    public function edit($data, $orgId)
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $data[0],
                'url'    => route('update-activity-default', [$orgId])
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
