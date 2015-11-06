<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class PeriodEnd
 * @package App\Core\V201\Forms\Activity
 */
class PeriodEnd extends Form
{
    public function buildForm()
    {
        $this
            ->add('date', 'date');
    }
}
