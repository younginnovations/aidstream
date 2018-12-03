<?php namespace App\Np\Forms\V202;

use App\Np\Forms\FormPathProvider;
use App\Np\Forms\NpBaseForm;

class Location extends NpBaseForm
{
    use FormPathProvider;

    public function buildForm()
    {
        $municipalitiesArray = collect(\DB::table('municipalities')->get());
        $municipalities = [];
        foreach ($municipalitiesArray as $municipality) {
            array_push($municipalities, $municipalities[$municipality->id] = $municipality->name);
        }

        $this->addSelect(
            'country',
            $this->getCodeList('Country', 'Organization'),
            trans('lite/elementForm.country'),
            null,
            'NP',
            true,
            ['wrapper' => ['class' => 'hidden form-group col-sm-6 country']]
        )->addSelect(
            'municipality',
           $municipalities,
            'Municipality',
            null,
            null,
            true,
            ['attr' => ['class' => 'municipality'], 'wrapper' =>['class' => 'form-group col-sm-6'], ]
        )->addSelect(
            'ward',
            [],
            'Ward',
            null,
            null,
            true,
            ['attr' => ['class' => 'wards', 'multiple' => 'multiple'],'wrapper' =>['class' => 'form-group ward col-sm-6'] ],
            true
        )->add(
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
        )->add(
            'add_map',
            'button',
            [
                'attr'    => ['class' => 'form-group view_map'],
                'label'   => trans('lite/elementForm.map_np'),
                'wrapper' => ['class' => 'form-group map-location']
            ]
        )->addToCollection('administrative', ' ', $this->getFormPath('Administrative'), 'collection_form administrative');
    }
}

