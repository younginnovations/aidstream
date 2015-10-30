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
    public function addCodeList($listName, $listType)
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
     * get percentage form
     * @return $this
     */
    public function addPercentage()
    {
        return $this->add(
            'percentage',
            'text'
        );
    }

    /**
     * get narrative form
     * @param        $className
     * @param string $label
     * @return $this
     */
    public function addNarrative($className, $label = 'Text')
    {
        return $this->add(
            'narrative',
            'collection',
            [
                'type'      => 'form',
                'prototype' => true,
                'options'   => [
                    'class' => 'App\Core\V201\Forms\Activity\Narrative',
                    'label' => false,
                    'data'  => ['label' => $label]
                ],
                'wrapper'   => [
                    'class' => "collection_form $className"
                ]
            ]
        );
    }

    /**
     * @param $folder
     * @return $this
     */
    public function addPeriodStart($folder)
    {
        return $this->add(
            'period_start',
            'collection',
            [
                'type'    => 'form',
                'options' => [
                    'class' => sprintf('App\Core\V201\Forms\%s\PeriodStart', $folder),
                    'label' => false,
                ]
            ]
        );
    }

    /**
     * @param $folder
     * @return $this
     */
    public function addPeriodEnd($folder)
    {
        return $this->add(
            'period_end',
            'collection',
            [
                'type'    => 'form',
                'options' => [
                    'class' => sprintf('App\Core\V201\Forms\%s\PeriodEnd', $folder),
                    'label' => false,
                ]
            ]
        );
    }

    /**
     * @param $folder
     * @return $this
     */
    public function addValue($folder)
    {
        return $this->add(
            'value',
            'collection',
            [
                'type'    => 'form',
                'options' => [
                    'class' => sprintf('App\Core\V201\Forms\%s\ValueForm', $folder),
                    'label' => false,
                ]
            ]
        );
    }

    /**
     * @param $folder
     * @return $this
     */
    public function addBudgetLine($folder)
    {
        return $this->add(
            'budget_line',
            'collection',
            [
                'type'    => 'form',
                'options' => [
                    'class' => sprintf('App\Core\V201\Forms\%s\BudgetLineForm', $folder),
                    'label' => false,
                ],
                'wrapper' => [
                    'class' => 'collection_form budget_line'
                ]
            ]
        );
    }

    /**
     * @param        $name
     * @param        $file
     * @param string $class
     * @return $this
     */
    public function addCollection($name, $file, $class = "")
    {
        return $this->add(
            $name,
            'collection',
            [
                'type'    => 'form',
                'options' => [
                    'class' => sprintf('App\Core\%s\Forms\%s', session()->get('version'), $file),
                    'label' => false,
                ],
                'wrapper' => [
                    'class' => sprintf('collection_form %s', $class)
                ]
            ]
        );
    }
}
