<?php namespace App\Np\Forms\V202\Np;

use App\Np\Forms\FormPathProvider;
use App\Np\Forms\NpBaseForm;
use App\Np\Forms\NpCustomizer;

class Location extends NpBaseForm
{
    use FormPathProvider, NpCustomizer;

    public function buildForm()
    {
        $municipalitiesArray = collect(\DB::table('municipalities')->get());
        $municipalities = [];

        foreach ($municipalitiesArray as $municipality) {
            $municipalities[$municipality->id] = $municipality->name;
        }
        $wardsArray = collect(\DB::table('municipalities')->select('wards', 'id', 'name')->get());
        $wards =[];
        $wards = $wardsArray->map(function ($ward) {
            $map = [];
            for ($i = 1; $i <= $ward->wards; $i++) {
                $wards[] = array("id" => $i, "text" => $i);
            }
            return $wards;
        });
        $wards = $wards;

        $this
        ->addSelect(
            'country',
            $this->getCodeList('Country', 'Organization'),
            trans('lite/elementForm.country'),
            null,
            null,
            true,
            ['wrapper' => ['class' => 'hidden country']]
        )
        ->addSelect(
            'municipality',
           $municipalities,
            'Municipality',
            null,
            null,
            true,
            ['attr' => ['class' => 'municipality'], 'wrapper' =>['class' => 'form-group col-sm-6'], ]
        )
        ->addSelect(
            'ward',
            [],
            'Ward',
            null,
            null,
            true,
            ['attr' => ['class' => 'wards', 'multiple' => 'multiple'],'wrapper' =>['class' => 'form-group ward col-sm-6'] ],
            true
        )->add(
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
