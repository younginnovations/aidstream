<?php namespace App\Np\Forms\V202;


use App\Np\Forms\FormPathProvider;
use App\Np\Forms\NpBaseForm;

class Administrative extends NpBaseForm
{
    use FormPathProvider;

    public function buildForm()
    {
        $this->addToCollection('point', ' ', $this->getFormPath('Point'), 'collection_form point');
    }
}