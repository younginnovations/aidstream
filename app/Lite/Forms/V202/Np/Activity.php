<?php namespace App\Lite\Forms\V202\Np;

use App\Lite\Forms\NpBaseForm;
use App\Lite\Forms\FormPathProvider;
use App\Lite\Forms\NpCustomizer;

/**
 * Class Activity
 * @package App\Lite\Forms\V202
 */
class Activity extends NpBaseForm
{
    use FormPathProvider, NpCustomizer;

    /**
     * Form structure for the Activity.
     */
    public function buildForm()
    {
        $participatingOrganisationFormPath = $this->getFormPath('ParticipatingOrganisation');
        $documentLinksFormPath             = $this->getFormPath('DocumentLinks');
        $locationFormPath                  = $this->getFormPath('Np\Location');

        $this->addText('activity_identifier', trans('lite/elementForm.activity_identifier'))
             ->addText('activity_title', trans('lite/elementForm.activity_title'))
             ->addSelect(
                 'activity_status',
                 $this->getCodeList('ActivityStatus', 'Activity'),
                 trans('lite/elementForm.activity_status'),
                 null,
                 null,
                 true,
                 ['wrapper' => ['class' => 'form-group col-sm-6']]
             )
             ->addSelect(
                 'sector',
                 $this->getCodeList('Sector', 'Activity'),
                 trans('lite/elementForm.sector'),
                 null,
                 null,
                 true,
                 ['attr' => ['multiple' => 'multiple'], 'wrapper' => ['class' => 'form-group col-sm-6']],
                 true
             )
             ->add('start_date', 'date', ['label' => trans('lite/elementForm.start_date'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->add('end_date', 'date', ['label' => trans('lite/elementForm.end_date'), 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->add('general_description', 'textarea', ['label' => trans('lite/elementForm.general_description'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->add('objectives', 'textarea', ['label' => trans('lite/elementForm.objectives'), 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->add('target_groups', 'textarea', ['label' => trans('lite/elementForm.target_groups'), 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->addToCollection('location', ' ', $locationFormPath, 'collection_form separator location')
             ->addButton('add_more_location', trans('lite/elementForm.add_another_country'), 'location', 'add_more')
             ->addToCollection('funding_organisations', ' ', $participatingOrganisationFormPath, 'collection_form separator funding_organisations')
             ->addButton('add_more_funding', trans('lite/elementForm.add_another_funding_organisation'), 'funding_organisations', 'add_more')
             ->addToCollection('implementing_organisations', ' ', $participatingOrganisationFormPath, 'collection_form separator implementing_organisations')
             ->addButton('add_more_implementing', trans('lite/elementForm.add_another_implementing_organisation'), 'implementing_organisations', 'add_more')
             ->addToCollection('outcomes_document', ' ', $documentLinksFormPath, 'collection_form outcomes_document')
             ->addToCollection('annual_report', ' ', $documentLinksFormPath, 'collection_form annual_report');

        $this->customize(['location']);
    }
}
