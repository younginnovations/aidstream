<?php namespace App\Lite\Forms\V202;


use App\Lite\Forms\LiteBaseForm;
use App\Lite\Forms\LiteCustomizer;

class Country extends LiteBaseForm
{
    use LiteCustomizer;

    public function buildForm()
    {
        $this->addSelect(
            'country',
            $this->getCodeList('Country', 'Organization'),
            trans('lite/elementForm.country'),
            null,
            null,
            true,
            ['wrapper' => ['class' => 'form-group col-sm-6']]
        );

        $this->customize(['country']);
    }
}

