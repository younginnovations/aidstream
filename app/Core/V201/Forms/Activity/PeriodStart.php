<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class PeriodStart
 * @package App\Core\V201\Forms\Activity
 */
class PeriodStart extends Form
{
    public function buildForm()
    {
        $this
            ->add('date', 'date');
    }
}
