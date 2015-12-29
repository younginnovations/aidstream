<?php namespace App\Core\V201\Forms;

use App\Core\Form\BaseForm;
use Illuminate\Database\DatabaseManager;

/**
 * Class SettingsForm
 * @package App\Core\V201\Forms
 */
class SettingsForm extends BaseForm
{
    protected $versions;

    /**
     * @param DatabaseManager $databaseManager
     */
    function __construct(DatabaseManager $databaseManager)
    {
        $db_versions = $databaseManager->table('versions')->get();
        $versions    = [];
        foreach ($db_versions as $ver) {
            $versions[$ver->version] = $ver->version;
        }
        $this->versions = $versions;
    }

    public function buildForm()
    {
        $this
            ->addCollection('version_form', 'Settings\VersionInfoForm', '', ['versions' => $this->versions])
            ->addCollection('reporting_organization_info', 'Settings\ReportingOrganizationInfoForm')
            ->addCollection('publishing_type', 'Settings\PublishingTypeForm')
            ->addCollection('registry_info', 'Settings\RegistryInfoForm')
            ->addCollection('default_field_values', 'Settings\DefaultFieldValuesForm')
            ->addCollection('default_field_groups', 'Settings\DefaultFieldGroupsForm')
            ->add(
                'Save',
                'submit',
                [
                    'attr' => ['class' => 'btn btn-submit btn-form']
                ]
            );
    }
}
