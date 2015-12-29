<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class DefaultFieldGroupsForm
 * @package App\Core\V201\Forms\Settings
 */
class DefaultFieldGroupsForm extends BaseForm
{
    /**
     * build default field group form
     */
    public function buildForm()
    {
        $this
            ->addFieldGroup('Identification')
            ->addFieldGroup('Basic Activity Information')
            ->addFieldGroup('Participating Organizations')
            ->addFieldGroup('Geopolitical Information')
            ->addFieldGroup('Classifications')
            ->addFieldGroup('Financial')
            ->addFieldGroup('Related Documents')
            ->addFieldGroup('Relations')
            ->addFieldGroup('Performance');
    }

    /**
     * return field group
     * @param $name
     * @return $this
     */
    private function addFieldGroup($name)
    {
        $rawPath        = sprintf('App\Core\%s\Forms\Settings\%s', '%s', str_replace(' ', '', $name));
        $currentVersion = session('version');
        $version        = class_exists(sprintf($rawPath, $currentVersion)) ? $currentVersion : config('app.default_version_name');

        return $this->add($name, 'form', ['class' => sprintf($rawPath, $version)]);
    }
}
