<?php
namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

class OtherIdentifierForm
{

    protected $formBuilder;
    protected $version;
    protected $formPath;

    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->version     = $version;
        $this->formPath    = $this->version->getActivityElement()->getOtherIdentifier()->getForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     */
    public function editForm($data, $activityId)
    {
        $modal['otherIdentifier'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => route('activity.other-identifier.update', [$activityId, 0])
            ]
        )->add('Save', 'submit');
    }
}
