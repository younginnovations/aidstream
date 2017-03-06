<?php namespace App\Lite\Forms\V202\Tz;


use App\Lite\Forms\FormPathProvider;
use App\Lite\Forms\LiteBaseForm;

class Administrative extends LiteBaseForm
{
    use FormPathProvider;

    public function buildForm()
    {
        $this->addSelect(
            'region',
            $this->loadCodeList('tz', 'region'),
            trans('lite/elementForm.region'),
            null,
            null,
            false,
            [
                'attr'    => ['class' => 'region'],
                'wrapper' => ['class' => 'col-sm-4 region-container']

            ]
        )
             ->addSelect(
                 'district',
                 [],
                 trans('lite/elementForm.district'),
                 null,
                 null,
                 false,
                 [
                     'attr'    => ['class' => 'district'],
                     'wrapper' => ['class' => 'col-sm-4 district-container']
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
             ->addToCollection('point', ' ', $this->getFormPath('Point'), 'collection_form point')
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

