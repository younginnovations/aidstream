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
            ->addFieldGroup('Identification', trans('element.identification'))
            ->addFieldGroup('Basic Activity Information', trans('element.basic_activity_information'))
            ->addFieldGroup('Participating Organizations', trans('element.participating_organisations'))
            ->addFieldGroup('Geopolitical Information' , trans('element.geopolitical_information'))
            ->addFieldGroup('Classifications', trans('element.classifications'))
            ->addFieldGroup('Financial', trans('element.financial'))
            ->addFieldGroup('Related Documents', trans('element.related_documents'))
            ->addFieldGroup('Relations', trans('element.relations'))
            ->addFieldGroup('Performance', trans('element.performance'));
    }

    /**
     * return field group
     * @param      $name
     * @param null $label
     * @return $this
     */
    private function addFieldGroup($name, $label = null)
    {
        $rawPath        = sprintf('App\Core\%s\Forms\Settings\%s', '%s', str_replace(' ', '', $name));
        $currentVersion = session('version');
        $version        = class_exists(sprintf($rawPath, $currentVersion)) ? $currentVersion : config('app.default_version_name');

        return $this->add($name, 'form', ['class' => sprintf($rawPath, $version), 'label' => $label]);
    }
}
