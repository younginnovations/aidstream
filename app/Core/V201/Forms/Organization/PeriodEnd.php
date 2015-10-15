<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class PeriodEnd extends Form
{
    public function buildForm()
    {
        $this
            ->add('date', 'date');
    }
}
