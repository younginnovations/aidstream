<?php namespace App\Np\Forms\V202;

use App\Core\Form\BaseForm;

/**
 * Class Settings
 * @package App\Np\Forms\V202
 */
class Settings extends BaseForm
{

    /**
     * Settings Form
     */
    public function buildForm()
    {
        $municipalitiesArray = collect(\DB::table('municipalities')->get());
        $municipalities = [];
        foreach ($municipalitiesArray as $municipality) {
                $municipalities[$municipality->id] = $municipality->name;
        }

        $orgId = session('org_id');
        $organizationData = collect(\DB::table('organization_location')->select()->where('organization_id','=',$orgId)->get());
        $municipality = [];

        $districts = \DB::table('organization_location')->join('districts', 'organization_location.district_id', '=', 'districts.id')->select('district_id','name')->first();

        $district["name"] = $districts->name;
        $district["id"]   = $districts->district_id;

        foreach ($organizationData as $key => $value) {
            $municipality[] = $value->municipality_id;
        }

        return $this
            ->add('organisationName', 'text', ['label' => trans('lite/settings.organisation_name'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6'], ])
            ->addSelect(
                'language',
                $this->getCodeList('Language', 'Organization'),
                trans('lite/settings.language'),
                $this->addHelpText('activity_defaults-default_language', true),
                config('app.default_language'),
                true,
                [
                    'wrapper' => ['class' => 'form-group col-sm-6']
                ]
            )
            ->add('organisationNameAbbreviation', 'text', ['label' => trans('lite/settings.organisation_name_abbreviation'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->addSelect(
                'organisationType',
                $this->getCodeList('OrganizationType', 'Organization'),
                trans('lite/settings.organisation_type'),
                null,
                config('app.default_language'),
                true,
                [
                    'wrapper' => ['class' => 'form-group col-sm-6']
                ]
            )
            ->addSelect(
                'country',
                $this->getCodeList('Country', 'Organization'),
                trans('lite/settings.country'),
                null,
                config('app.default_language'),
                true,
                [
                    'wrapper' => ['class' => 'form-group col-sm-6 country'],
                ]
            )
            ->addSelect(
                'district',
                $this->getCodeList('Districts','Organization'),
                trans('np/global.district'),
                null,
                $district['id'],
                true,
                [
                    'wrapper'    => ['class' => 'form-group col-sm-6 district']
                ],
                false
            )
            ->addSelect(
                'working_district',
                $this->getCodeList('Districts','Organization'),
                trans('np/global.working_district'),
                null,
                $district['id'],
                true,
                [
                    'wrapper'    => ['class' => 'form-group col-sm-6']
                ],
                false
            )
            ->addSelect(
                'municipality',
                $municipalities,
                trans('np/global.working_municipality'),
                null,
                config('app.default_language'),
                true,
                [
                    'attr'    =>['multiple' => 'multiple'],
                    'wrapper' => ['class' => 'form-group col-sm-6 municipality'],
                    'selected'=> $municipality
                ]
            )
            ->add(
                'organisationRegistrationAgency',
                'select',
                [
                    'label' => trans('lite/settings.organisation_registration_agency'),
                    'required' => true,
                    'wrapper' => ['class' => 'form-group col-sm-6 organization_registration_agency']
                ]
            )
            ->add(
                'organisationRegistrationNumber',
                'text',
                [
                    'label'    => trans('lite/settings.organisation_registration_number'),
                    'required' => true,
                    'wrapper'  => ['class' => 'form-group col-sm-6 registration_number']
                ]
            )
            ->add(
                'organisationIatiIdentifier',
                'text',
                [
                    'label'      => trans('lite/settings.organisation_iati_identifier'),
                    'required'   => true,
                    'help_block' => $this->addHelpText('registration_org_identifier', true),
                    'attr'       => ['readonly'],
                    'wrapper'    => ['class' => 'form-group col-sm-6 organization_identifier']
                ]
            )
            ->add('publisherId', 'text', ['label' => trans('lite/settings.publisher_id'), 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add('apiKey', 'text', ['label' => trans('lite/settings.api_key'), 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add(
                'publishFile',
                'choice',
                [
                    'label'         => trans('lite/settings.automatically_update_iati_text'),
                    'choices'       => ['yes' => trans('lite/settings.yes'), 'no' => trans('lite/settings.no')],
                    'expanded'      => true,
                    'default_value' => 'no',
                    'wrapper'       => ['class' => 'form-group col-sm-6'],
                ]
            )
            ->addSelect(
                'defaultCurrency',
                $this->getCodeList('Currency', 'Organization'),
                trans('lite/settings.default_currency'),
                $this->addHelpText('activity_defaults-default_currency', true),
                config('app.default_language'),
                true,
                [
                    'wrapper' => ['class' => 'form-group col-sm-6']
                ]
            )
            ->addSelect(
                'defaultLanguage',
                $this->getCodeList('Language', 'Organization'),
                trans('lite/settings.default_language'),
                $this->addHelpText('activity_defaults-default_language', true),
                config('app.default_language'),
                true,
                [
                    'wrapper' => ['class' => 'form-group col-sm-6']
                ]
            )
            ->add(trans('lite/settings.save'), 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
