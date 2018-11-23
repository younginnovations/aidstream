<?php namespace App\Lite\Forms\V202\Np;

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
        )
        ->addSelect(
                 'municipality',
                 $this->getCodeList('Sector', 'Activity'),
                 'Municipality',
                 null,
                 null,
                 true,
                 ['attr' => ['multiple' => 'multiple'], 'wrapper' => ['class' => 'form-group col-sm-6']],
                 true
        )
        ->addSelect(
                 'district',
                 $this->getCodeList('Sector', 'Activity'),
                 'District',
                 null,
                 null,
                 true,
                 ['attr' => ['multiple' => 'multiple'], 'wrapper' => ['class' => 'form-group col-sm-6']],
                 true
             )
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
        ;
    }
}
