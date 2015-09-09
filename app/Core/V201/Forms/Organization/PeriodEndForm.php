<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class PeriodEndForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('date', 'date');
    }
}