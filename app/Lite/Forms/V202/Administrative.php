<?php namespace App\Lite\Forms\V202;


use App\Lite\Forms\FormPathProvider;
use App\Lite\Forms\LiteBaseForm;

class Administrative extends LiteBaseForm
{
    use FormPathProvider;

    public function buildForm()
    {
        $this->addToCollection('point', ' ', $this->getFormPath('Point'), 'collection_form point');
    }
}