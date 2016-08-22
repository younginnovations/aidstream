<?php namespace App\Core\Form;

use Kris\LaravelFormBuilder\Form;

/**
 * Class BaseForm
 * @package App\Core
 */
class BaseForm extends Form
{
    /**
     * Set model to form object
     *
     * @param mixed $model
     * @return $this
     */
    public function setModel($model)
    {
        parent::setModel($model);
        $oldData     = old();
        $this->model = $oldData ? $oldData : $model;
    }

    /**
     * return codeList array from json codeList
     * @param      $listName
     * @param      $listType
     * @param bool $code
     * @return array
     */
    public function getCodeList($listName, $listType, $code = true)
    {
        $defaultVersion = config('app.default_version_name');
        $defaultLocale  = config('app.fallback_locale');
        $version        = session()->get('version');
        $locale         = config('app.locale');
        $rawFilePath    = app_path("Core/%s/Codelist/%s/$listType/$listName.json");
        $filePath       = sprintf($rawFilePath, $version, $locale);
        file_exists($filePath) ?: $filePath = sprintf($rawFilePath, $version, $defaultLocale);
        file_exists($filePath) ?: $filePath = sprintf($rawFilePath, $defaultVersion, $locale);
        file_exists($filePath) ?: $filePath = sprintf($rawFilePath, $defaultVersion, $defaultLocale);
        $codeListFromFile = file_get_contents($filePath);
        $codeLists        = json_decode($codeListFromFile, true);
        $codeList         = $codeLists[$listName];
        $data             = [];

        foreach ($codeList as $list) {
            $data[$list['code']] = ($code) ? $list['code'] . (array_key_exists(
                    'name',
                    $list
                ) ? ' - ' . $list['name'] : '') : $list['name'];
        }

        return $data;
    }

    /**
     * adds add more button to form
     * @param        $buttonId
     * @param        $formClass
     * @param string $label
     * @return $this
     */
    protected function addAddMoreButton($buttonId, $formClass, $label = 'Add More')
    {
        return $this->add(
            $buttonId,
            'button',
            [
                'label' => $label,
                'attr'  => [
                    'class'           => 'add_to_collection',
                    'data-collection' => $formClass,
                ],
            ]
        );
    }

    /**
     * adds remove this button to form
     * @param $buttonId
     * @return $this
     */
    protected function addRemoveThisButton($buttonId)
    {
        return $this->add(
            $buttonId,
            'button',
            [
                'label' => 'Remove This',
                'attr'  => [
                    'class' => 'remove_from_collection',
                ],
            ]
        );
    }

    /**
     * @param        $className
     * @param string $label
     * @param array  $data
     * @return BaseForm
     */
    protected function addNarrative($className, $label = 'Text', $data = [])
    {
        $data['label'] = $label;

        return $this->addCollection('narrative', 'Activity\Narrative', $className, $data);
    }

    /**
     * @param        $name
     * @param        $file
     * @param string $class
     * @param array  $data
     * @param bool   $label
     * @return $this
     */
    public function addCollection($name, $file, $class = "", array $data = [], $label = null)
    {
        $class .= ($class ? ' has_add_more' : '');
        $defaultVersion = config('app.default_version_name');
        $filePath       = sprintf('App\Core\%s\Forms\%s', session()->get('version'), $file);
        $FormClass      = !class_exists($filePath) ? sprintf(
            'App\Core\%s\Forms\%s',
            $defaultVersion,
            $file
        ) : $filePath;

        return $this->add(
            $name,
            'collection',
            [
                'type'    => 'form',
                'options' => [
                    'class' => $FormClass,
                    'data'  => $data,
                    'label' => false,
                ],
                'label'   => $label,
                'wrapper' => [
                    'class' => sprintf('collection_form %s', $class),
                ],
            ]
        );
    }

    /**
     * @param      $name
     * @param      $value
     * @param null $checked
     * @param null $readonly
     * @return $this
     */
    protected function addCheckBox($name, $value, $checked = null, $readonly = null)
    {
        return $this->add(
            $name,
            'checkbox',
            ['value' => $value, 'checked' => $checked, 'attr' => ['class' => 'field1', 'readonly' => $readonly]]
        );
    }

    /**
     * @param       $name
     * @param array $choices
     * @param null  $label
     * @param null  $helpText
     * @param null  $defaultValue
     * @param bool  $required
     * @param array $customOptions
     * @return $this
     */
    protected function addSelect(
        $name,
        array $choices,
        $label = null,
        $helpText = null,
        $defaultValue = null,
        $required = false,
        $customOptions = []
    ) {
        $options = [
            'choices'       => $choices,
            'label'         => $label,
            'empty_value'   => 'Select one of the following options',
            'default_value' => $defaultValue,
            'help_block'    => $helpText,
            'required'      => $required
        ];

        $options = array_merge($options, $customOptions);

        return $this->add(
            $name,
            'select',
            $options
        );
    }

    /**
     * @param null $helpText
     * @return $this
     */
    protected function addPercentage($helpText = null)
    {
        return $this->add('percentage', 'text', ['help_block' => $helpText]);
    }

    /**
     * @param string $name
     * @return $this
     */
    protected function addTitleCollection($name = 'title')
    {
        return $this->addCollection($name, 'Activity\Title');
    }

    /**
     * @return $this
     */
    protected function addSaveButton()
    {
        return $this->add('Save', 'submit', ['attr' => ['class' => 'btn btn-primary btn-form btn-submit']]);
    }

    /**
     * add help text in the form fields
     * @param      $helpText
     * @return array
     */
    protected function addHelpText($helpText, $tooltip = true)
    {
        $help = trans(session()->get('version') . "/help");
        is_array($help) ?: $help = trans(config('app.default_version_name') . '/help');
        if (!isset($help[$helpText])) {
            return null;
        }

        $attr = [
            'class' => 'help-block',
        ];

        if ($tooltip) {
            $attr = array_merge(
                $attr,
                [
                    'class'          => 'help-text',
                    'title'          => htmlspecialchars($help[$helpText]),
                    'data-toggle'    => 'tooltip',
                    'data-placement' => 'top',
                ]
            );
        }

        return ['text' => $help[$helpText], 'tag' => 'span', 'attr' => $attr];
    }
}
