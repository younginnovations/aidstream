<?php
namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class OtherIdentifierForm
 * @package App\Services\FormCreator\Activity
 */
class OtherIdentifierForm
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
        $this->formPath    = $this->version->getActivityElement()->getOtherIdentifier()->getForm();
    }

    /**
     * @param $data
     * @param       $activityId
     * @return $this
     * return other identifier edit form.
     */
    public function editForm($data, $activityId)
    {
        $modal['other_identifier'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => route('activity.other-identifier.update', [$activityId, 0])
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
