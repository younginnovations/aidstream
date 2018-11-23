<?php namespace App\Np\Forms\V202;


use App\Np\Forms\NpBaseForm;
use App\Np\Forms\NpCustomizer;

class Country extends NpBaseForm
{
    use NpCustomizer;

    public function buildForm()
    {
        $this->addSelect(
            'country',
            $this->getCodeList('Country', 'Organization'),
            trans('lite/elementForm.country'),
            null,
            null,
            false,
            ['wrapper' => ['class' => 'form-group col-sm-6']]
        );

        $this->customize(['country']);
    }
}

