<?php namespace App\Core\V203\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class Description
 * @package App\Core\V201\Forms\Activity
 */
class Description extends BaseForm
{
    /**
     * builds activity transaction description form
     */
    public function buildForm()
    {
        $this
            ->addNarrative('description_narrative')
            ->addAddMoreButton('add_narrative', 'description_narrative');
    }
}
