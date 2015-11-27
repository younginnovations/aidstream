<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class CapitalSpend
 * @package App\Core\V201\Forms\Activity
 */
class CapitalSpend extends Form
{
    /**
     * builds the Activity Capital Spend form
     */
    public function buildForm()
    {
        $this->add('capital_spend', 'text', ['label' => 'Percentage']);
    }
}
