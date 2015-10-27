<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Department
 * @package App\Core\V201\Forms\Activity
 */
class Department extends BaseForm
{
    /**
     * builds the contact info department form
     */
    public function buildForm()
    {
        $this
            ->addNarrative('departmentNarrative')
            ->addAddMoreButton('add_departmentNarrative', 'departmentNarrative');
    }
}
