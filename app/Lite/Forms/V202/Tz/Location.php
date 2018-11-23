<?php namespace App\Lite\Forms\V202\Tz;

use App\Lite\Forms\FormPathProvider;
use App\Lite\Forms\LiteBaseForm;
use App\Lite\Forms\LiteCustomizer;

class Location extends LiteBaseForm
{
    use FormPathProvider, LiteCustomizer;

    public function buildForm()
    {
        $this
        ->addSelect(
            'country',
            $this->getCodeList('Country', 'Organization'),
            trans('lite/elementForm.country'),
            null,
            null,
            true,
            ['wrapper' => ['class' => 'form-group col-sm-6 country']]
        )
             ->addToCollection('administrative', ' ', $this->getFormPath('Administrative'), 'collection_form administrative')
             ->add(
                 'add_more_administrative',
                 'button',
                 [
                     'attr'    => ['class' => 'form-group add_another_location'],
                     'label'   => trans('lite/elementForm.add_another_location'),
                     'wrapper' => ['class' => 'form-group']
                 ]
             );
//             ->addButton('add_more_administrative', trans('lite/elementForm.add_another_location'), 'administrative', 'add_more');
//        $this->customize(['country']);
    }
}
