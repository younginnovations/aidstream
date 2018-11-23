<?php namespace App\Np\Forms\V202;


use App\Np\Forms\NpBaseForm;

/**
 * Class ParticipatingOrganisation
 * @package App\Np\Forms\V202
 */
class ParticipatingOrganisation extends NpBaseForm
{
    /**
     * Form structure for funding organisation and implementing organisation.
     */
    public function buildForm()
    {
        $required              = true;
        $organizationNameLabel = trans('lite/elementForm.implementing_organisation_name');
        $organizationTypeLabel = trans('lite/elementForm.implementing_organisation_type');

        if (substr($this->name, 0, 21) == 'funding_organisations') {
            $required              = false;
            $organizationNameLabel = trans('lite/elementForm.funding_organisation_name');
            $organizationTypeLabel = trans('lite/elementForm.funding_organisation_type');
        }

        $organisationTypes = $this->getCodeList('OrganisationType', 'Activity');

        $this->addText('organisation_name', $organizationNameLabel, $required)
             ->addSelect(
                 'organisation_type',
                 $organisationTypes,
                 $organizationTypeLabel,
                 null,
                 null,
                 $required,
                 [
                     'wrapper' => ['class' => 'form-group col-sm-6']
                 ]
             )
             ->add(
                 'remove_button',
                 'button',
                 [
                     'label' => 'Remove This',
                     'attr'  => [
                         'class' => 'remove_from_collection',
                     ],
                 ]
             );
    }
}