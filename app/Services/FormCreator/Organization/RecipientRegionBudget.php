<?php namespace App\Services\FormCreator\Organization;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class RecipientRegionBudgetForm
 * @package App\Services\FormCreator\Organization
 */
class RecipientRegionBudget
{

    /**
     * @var FormBuilder
     */
    protected $formBuilder;
    /**
     * @var Version
     */
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
        $this->formPath    = $this->version->getOrganizationElement()->getRecipientRegionBudgetsForm();
    }

    /**
     * @param array $data
     * @param       $organizationId
     * @return $this
     */
    public function editForm($data, $organizationId)
    {
        $modal['recipient_region_budget'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => route('organization.recipient-region-budget.update', [$organizationId, 0])
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
