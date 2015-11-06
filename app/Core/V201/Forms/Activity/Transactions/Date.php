<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class Date
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class Date extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds Transaction date form
     */
    public function buildForm()
    {
        $this
            ->add('date', 'date');
    }
}
