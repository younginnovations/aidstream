<?php
namespace App\Services\FormCreator\Organization;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

class RecipientCountryBudgetForm
{

    protected $formBuilder;
    protected $version;
    protected $formPath;

    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->version     = $version;
        $this->formPath    = $this->version->getOrganizationElement()->getRecipientCountryBudget()->getForm();
    }

    /**
     * @param array $data
     * @param       $organizationId
     * @return $this
     */
    public function editForm($data, $organizationId)
    {
        $modal['recipient_country_budget'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => route('organization.recipient-country-budget.update', [$organizationId, 0])
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form'],'label' => trans('global.save')])
            ->add('Cancel', 'static', [
                'tag'     => 'a',
                'label'   => false,
                'value'   =>  trans('global.cancel'),
                'attr'    => [
                    'class' => 'btn btn-cancel',
                    'href'  => route('organization.show', $organizationId)
                ],
                'wrapper' => false
            ]);
    }
}
