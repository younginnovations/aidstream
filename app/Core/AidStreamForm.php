<?php namespace App\Core;

use Kris\LaravelFormBuilder\Form;

/**
 * Class AidStreamForm
 * @package App\Core
 */
class AidStreamForm extends Form
{

    /**
     * adds add more button to form
     * @param $buttonId
     * @param $formClass
     * @return $this
     */
    public function addButton($buttonId, $formClass)
    {
        return $this->add(
            $buttonId,
            'button',
            [
                'label' => 'Add More',
                'attr' => [
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
    public function removeButton($buttonId)
    {
        return $this->add(
            $buttonId,
            'button',
            [
                'label' => 'Remove This',
                'attr' => [
                    'class' => 'remove_from_collection',
                ]
            ]
        );
    }

}