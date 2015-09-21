<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class PeriodStartForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('date', 'date');
    }
}