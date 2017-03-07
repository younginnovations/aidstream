<?php namespace App\Lite\Forms\V202;


use App\Lite\Forms\FormPathProvider;
use App\Lite\Forms\LiteBaseForm;

class Administrative extends LiteBaseForm
{
    use FormPathProvider;

    public function buildForm()
    {
        $this->add(
            'add_map',
            'button',
            [
                'attr'    => ['class' => 'form-group view_map'],
                'label'   => trans('lite/elementForm.map'),
                'wrapper' => ['class' => 'form-group map-location']
            ]
        )
             ->addToCollection('point', ' ', $this->getFormPath('Point'), 'collection_form point');
    }
}