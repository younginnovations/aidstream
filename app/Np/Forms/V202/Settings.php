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
            ->add(
                'organisationRegistrationAgency',
                'select',
                [
                    'label' => trans('lite/settings.organisation_registration_agency'),
                    'required' => true,
                    'empty_value' => 'Select an agency',
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
