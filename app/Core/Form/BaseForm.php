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
            app_path("Core/V201/Codelist/" . config('app.locale') . "/$codeListType/$codeListName.json")
        );
        $codeListData    = json_decode($codeListContent, true);
        $codeList        = $codeListData[$codeListName];
        $data            = [];

        foreach ($codeList as $list) {
            $data[$list['code']] = $list['code'] . ' - ' . $list['name'];
        }

        return $data;
    }

}
