<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Name
 * @package App\Core\V201\Forms\Activity
 */
class Name extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds name form
     */
    public function buildForm()
    {
        $this
            ->addNarrative('name_narrative')
            ->addAddMoreButton('add', 'name_narrative');
    }
}
