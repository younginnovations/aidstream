<?php namespace App\Services\FormCreator\Organization;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

/**
 * Class TotalExpenditure
 * @package App\Services\FormCreator\Organization
 */
class TotalExpenditure
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
        $this->formPath    = $version->getOrganizationElement()->getTotalExpendituresForm();
    }

    /**
     * @param array $data
     * @param       $organizationId
     * @return $this
     */
    public function editForm($data, $organizationId)
    {
        $modal['total_expenditure'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => route('organization.total-expenditure.update', [$organizationId, 0])
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
