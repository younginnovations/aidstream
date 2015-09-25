<?php
namespace app\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

class Identifier
{
    protected $formBuilder;
    protected $version;
    protected $formPath;

    public function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->version     = $version;
        $this->formPath    = $this->version->getActivityElement()->getIdentifier()->getForm();
    }

    public function create()
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'POST',
                'url'    => route('activity.store')
            ]
        )->add('Save', 'submit');
    }

    public function editForm($data, $organizationId)
    {
        /*        $modal['name'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => route('organization.name.update', [$organizationId, 0])
            ]
        )->add('Save', 'submit');*/
    }
}
