<?php namespace App\Core\Form;

use Kris\LaravelFormBuilder\Form;

/**
 * Class BaseForm
 * @package App\Core
 */
class BaseForm extends Form
{

    /**
     * adds add more button to form
     * @param $buttonId
     * @param $formClass
     * @return $this
     */
    public function addAddMoreButton($buttonId, $formClass)
    {
        return $this->add(
            $buttonId,
            'button',
            [
                'label' => 'Add More',
                'attr'  => [
                    'class'           => 'add_to_collection',
                    'data-collection' => $formClass
                ]
            ]
        );
    }

    /**
     * adds remove this button to form
     * @param $buttonId
     * @return $this
     */
    public function addRemoveThisButton($buttonId)
    {
        return $this->add(
            $buttonId,
            'button',
            [
                'label' => 'Remove This',
                'attr'  => [
                    'class' => 'remove_from_collection',
                ]
            ]
        );
    }

    /**
     * return codeList array from json codeList
     * @param $listName
     * @param $listType
     * @return array
     */
    public function getCodeList($listName, $listType)
    {
        $codeListFromFile = file_get_contents(
            app_path(
                "Core/" . session()->get('version') . "/Codelist/" . config('app.locale') . "/$listType/$listName.json"
            )
        );
        $codeLists        = json_decode($codeListFromFile, true);
        $codeList         = $codeLists[$listName];
        $data             = [];

        foreach ($codeList as $list) {
            $data[$list['code']] = $list['code'] . (array_key_exists('name', $list) ? ' - ' . $list['name'] : '');
        }

        return $data;
    }

    /**
     * @param        $className
     * @param string $label
     * @return BaseForm
     */
    public function addNarrative($className, $label = 'Text')
    {
        return $this->addCollection('narrative', 'Activity\Narrative', $className, ['label' => $label]);
    }

    /**
     * @param        $name
     * @param        $file
     * @param string $class
     * @param array  $data
     * @param bool   $label
     * @return $this
     */
    public function addCollection($name, $file, $class = "", $data = [], $label = null)
    {
        return $this->add(
            $name,
            'collection',
            [
                'type'    => 'form',
                'options' => [
                    'class' => sprintf('App\Core\%s\Forms\%s', session()->get('version'), $file),
                    'data'  => $data,
                    'label' => false,
                ],
                'label'   => $label,
                'wrapper' => [
                    'class' => sprintf('collection_form %s', $class)
                ]
            ]
        );
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function addCheckBox($name, $value)
    {
        return $this->add(
            $name,
            'checkbox',
            [
                'value' => $value,
                'attr'  => ['class' => 'field1']
            ]
        );
    }

    /**
     * @param string $value
     * @return $this
     */
    public function addPercentage($value = 'percentage')
    {
        return $this->add(
            $value,
            'text'
        );
    }

    public function addSaveButton()
    {
        return $this->add(
            'Save',
            'submit',
            [
                'attr' => ['class' => 'btn btn-primary']
            ]
        );
    }
}
