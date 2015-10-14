<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class Sectors extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('sector', 'Activity\Sector', 'sector')
            ->addAddMoreButton('add', 'sector');
    }
}
