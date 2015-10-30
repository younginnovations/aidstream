<?php namespace App\Core\V201\Forms;

use App\Core\Form\BaseForm;
use Illuminate\Database\DatabaseManager;

/**
 * Class SettingsForm
 * @package App\Core\V201\Forms
 */
class SettingsForm extends BaseForm
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

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
        $this->versions        = $versions;
        $this->databaseManager = $databaseManager;
    }

    public function buildForm()
    {
        $this
            ->addCollection('version_form', 'Settings\VersionInfoForm', '', ['versions' => $this->versions], false)
            ->addCollection('reporting_organization_info', 'Organization\ReportingOrganizationInfoForm', '', [], false)
            ->addCollection('publishing_type', 'Settings\PublishingTypeForm', '', [], false)
            ->addCollection('registry_info', 'Settings\RegistryInfoForm', '', [], false)
            ->addCollection('default_field_values', 'Settings\DefaultFieldValuesForm', '', [], false)
            ->addCollection('default_field_groups', 'Settings\DefaultFieldGroupsForm', '', [], false)
            ->add(
                'Save',
                'submit',
                [
                    'attr' => ['class' => 'btn btn-primary']
                ]
            );
    }
}
