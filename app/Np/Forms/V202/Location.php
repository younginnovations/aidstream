<?php namespace App\Np\Forms\V202;

use App\Np\Forms\FormPathProvider;
use App\Np\Forms\NpBaseForm;

class Location extends NpBaseForm
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
        )
             ->add(
                 'map',
                 'static',
                 [
                     'label'   => false,
                     'attr'    => [
                         'class' => 'map_container',
                         'style' => 'height: 400px; width:400px'
                     ],
                     'wrapper' => ['class' => 'form-group full-width-wrap map-wrapper']
                 ]
             )
             ->add(
                 'add_map',
                 'button',
                 [
                     'attr'    => ['class' => 'form-group view_map'],
                     'label'   => trans('lite/elementForm.map'),
                     'wrapper' => ['class' => 'form-group map-location']
                 ]
             )
             ->addToCollection('administrative', ' ', $this->getFormPath('Administrative'), 'collection_form administrative')
             ->add(
                 'remove_button',
                 'button',
                 [
                     'label' => 'Remove This',
                     'attr'  => [
                         'class' => 'remove_from_collection',
                     ],
                 ]
             );
    }
}

