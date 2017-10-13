<?php namespace App\Core\V201\Forms\Settings;


use App\Core\Form\BaseForm;
use App\Models\Settings;

class DefaultValues extends BaseForm
{

    protected $settings;
    protected $defaultFieldGroups;

    public function __construct(Settings $settings)
    {
        $this->settings           = $settings->where('organization_id', session('org_id'))->first();
        $this->defaultFieldGroups = (array) (($this->settings) ? $this->settings->default_field_groups : []);
    }

    public function buildForm()
    {
        $this->addSelect(
            'default_currency',
            $this->getCodeList('Currency', 'Organization'),
            trans('elementForm.default_currency'),
            $this->addHelpText('activity_defaults-default_currency', false),
            null,
            true,
            [
                'wrapper' => ['class' => 'form-group col-md-6']
            ]
        )
             ->addSelect(
                 'default_language',
                 $this->getCodeList('Language', 'Organization'),
                 trans('elementForm.default_language'),
                 $this->addHelpText('activity_defaults-default_language', false),
                 config('app.default_language'),
                 true,
                 [
                     'wrapper' => ['class' => 'form-group col-md-6']
                 ]

             )
             ->add(
                 'default_hierarchy',
                 'text',
                 [
                     'help_block'    => $this->addHelpText('activity_defaults-hierarchy', false),
                     'wrapper'       => ['class' => 'form-group col-md-6'],
                     'default_value' => 1,
                     'attr'          => ['readonly' => true],
                     'label'         => trans('elementForm.default_hierarchy')
                 ]
             )
             ->add('linked_data_uri', 'text', ['label' => trans('elementForm.linked_data_uri'), 'wrapper' => ['class' => 'form-group col-md-6']]);
        $this->addSelect(
            'default_collaboration_type',
            $this->getCodeList('CollaborationType', 'Organization'),
            trans('elementForm.default_collaboration_type'),
            $this->addHelpText('activity_defaults-default_collaboration_type', false),
            null,
            false,
            getVal($this->defaultFieldGroups, [0, 'Classifications', 'collaboration_type']) == ""
                ? ['wrapper' => ['class' => 'form-group col-md-6 hidden']]
                : ['wrapper' => ['class' => 'from-group col-md-6']]
        );
        $this->addSelect(
            'default_flow_type',
            $this->getCodeList('FlowType', 'Organization'),
            trans('elementForm.default_flow_type'),
            $this->addHelpText('activity_defaults-default_flow_type', false),
            null,
            false,
            (getVal($this->defaultFieldGroups, [0, 'Classifications', 'default_flow_type']) == "")
                ? ['wrapper' => ['class' => 'form-group col-md-6 hidden']]
                : ['wrapper' => ['class' => 'form-group col-md-6']]
        );
        $this->addSelect(
            'default_finance_type',
            $this->getCodeList('FinanceType', 'Organization'),
            trans('elementForm.default_finance_type'),
            $this->addHelpText('activity_defaults-default_finance_type', false),
            null,
            false,
            (getVal($this->defaultFieldGroups, [0, 'Classifications', 'default_finance_type']) == "")
                ? ['wrapper' => ['class' => 'form-group col-md-6 hidden']]
                : ['wrapper' => ['class' => 'form-group col-md-6']]
        );
        $this->addSelect(
            'default_aid_type',
            $this->getCodeList('AidType', 'Organization'),
            trans('elementForm.default_aid_type'),
            $this->addHelpText('activity_defaults-default_aid_type', false),
            null,
            false,
            (getVal($this->defaultFieldGroups, [0, 'Classifications', 'default_aid_type']) == "")
                ? ['wrapper' => ['class' => 'form-group col-md-6 hidden']]
                : ['wrapper' => ['class' => 'form-group col-md-6']]
        );
        $this->addSelect(
            'default_tied_status',
            $this->getCodeList('TiedStatus', 'Organization'),
            trans('elementForm.default_tied_status'),
            $this->addHelpText('activity_defaults-default_tied_status', false),
            null,
            false,
            (getVal($this->defaultFieldGroups, [0, 'Classifications', 'default_tied_status']) == "")
                ? ['wrapper' => ['class' => 'form-group col-md-6 hidden']]
                : ['wrapper ' => ['class' => 'form-group col-md-6']]
        );
        $this->add(
            'save',
            'submit',
            [
                'label'   => trans('global.save_default_values'),
                'attr'    => ['class' => 'btn btn-primary btn-submit btn-form'],
                'wrapper' => ['class' => 'form-group']

            ]
        );

    }
}