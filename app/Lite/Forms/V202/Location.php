<?php namespace App\Lite\Forms\V202;


use App\Lite\Forms\FormPathProvider;
use App\Lite\Forms\LiteBaseForm;


class Location extends LiteBaseForm
{
    use FormPathProvider;

    public function buildForm()
    {
        $this->addSelect(
            'country',
            $this->getCodeList('Country', 'Organization'),
            trans('lite/elementForm.country'),
            null,
            null,
            true,
            ['wrapper' => ['class' => 'form-group col-sm-6 country']]
        )->addToCollection('administrative', ' ', $this->getFormPath('Administrative'), 'collection_form administrative')
             ->add(
                 'remove_button',
                 'button',
                 [
                     'label' => 'Remove This',
                     'attr'  => [
                         'class' => 'remove_from_collection',
                     ],
                 ]
             );;
    }
}

