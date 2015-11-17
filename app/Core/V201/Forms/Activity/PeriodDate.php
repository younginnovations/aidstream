<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class PeriodDate
 * Contains the function to create the period date form
 * @package App\Core\V201\Forms\Activity
 */
class PeriodDate extends Form
{
    /**
     * builds the activity period date form
     */
    public function buildForm()
    {
        $this->add('date', 'date');
    }
}
