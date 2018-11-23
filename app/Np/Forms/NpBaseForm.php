<?php namespace App\Np\Forms;


use App\Core\Form\BaseForm;

/**
 * Class NpBaseForm
 * @package App\Np\Forms
 */
class NpBaseForm extends BaseForm
{
    /**
     * Add text field in the form.
     *
     * @param            $name
     * @param            $label
     * @param bool       $required
     * @param string     $wrapperClass
     * @param array      $attr
     * @return $this
     */
    public function addText($name, $label, $required = true, $wrapperClass = 'form-group col-sm-6', $attr = [])
    {
        return $this->add($name, 'text', ['attr' => $attr, 'label' => $label, 'required' => $required, 'wrapper' => ['class' => $wrapperClass]]);
    }

    /**
     * Add Collection in the form.
     *
     * @param        $name
     * @param        $label
     * @param        $childFormPath
     * @param string $wrapperClass
     * @return $this
     */
    public function addToCollection($name, $label, $childFormPath, $wrapperClass = 'collection_form')
    {
        return $this->add(
            $name,
            'collection',
            [
                'label'   => $label,
                'type'    => 'form',
                'options' => [
                    'class' => $childFormPath,
                    'label' => false
                ],
                'wrapper' => [
                    'class' => $wrapperClass
                ]
            ]
        );
    }

    /**
     * Add add more button in the form.
     *
     * @param $name
     * @param $label
     * @param $dataCollection
     * @param $buttonType
     * @return $this
     */
    public function addButton($name, $label, $dataCollection, $buttonType)
    {
        $class = ($buttonType === 'add_more') ? 'add-to-collection' : 'remove_from_collection';

        return $this->add(
            $name,
            'button',
            [
                'label' => $label,
                'attr'  => [
                    'data-collection' => $dataCollection,
                    'class'           => $class
                ]
            ]
        );
    }

    /**
     * Add Password field in the form.
     *
     * @param        $name
     * @param        $label
     * @param bool   $required
     * @param string $wrapperClass
     * @return $this
     */
    public function addPassword($name, $label, $required = true, $wrapperClass = 'form-group col-sm-6')
    {
        return $this->add($name, 'password', ['label' => $label, 'required' => $required, 'wrapper' => ['class' => $wrapperClass]]);
    }

    public function loadCodeList($systemVersion, $name)
    {
        return config(sprintf('%s.%s', $systemVersion, $name));
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
        $customOptions = [],
        $emptyValue = null
    ) {
        $options = [
            'choices'       => $choices,
            'label'         => $label,
            'empty_value'   => $emptyValue ? '' : trans('elementForm.select_text'),
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
}

