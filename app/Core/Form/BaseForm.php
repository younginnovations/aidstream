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
     * @param $codeListName
     * @return array
     */
    public function getCodeList($codeListName, $codeListType)
    {
        $codeListContent = file_get_contents(
            app_path(
                "Core/" . session()->get('version') . "/Codelist/" . config('app.locale') . "/$codeListType/$codeListName.json"
            )
        );
        $codeListData    = json_decode($codeListContent, true);
        $codeList        = $codeListData[$codeListName];
        $data            = [];

        foreach ($codeList as $list) {
            $data[$list['code']] = $list['code'] . ' - ' . $list['name'];
        }

        return $data;
    }

    /**
     * get percentage form
     * @return $this
     */
    public function getPercentage()
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
    public function getNarrative($className, $label = 'text')
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
                    'data' => ['label' => $label]
                ],
                'wrapper'   => [
                    'class' => "collection_form $className"
                ]
            ]
        );
    }
}
