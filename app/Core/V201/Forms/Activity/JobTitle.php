<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class JobTitle
 * @package App\Core\V201\Forms\Activity
 */
class JobTitle extends BaseForm
{
    /**
     * builds the contact info Job Title form
     */
    public function buildForm()
    {
        $this
            ->getNarrative('jobTitleNarrative')
            ->addAddMoreButton('add_jobTitleNarrative', 'jobTitleNarrative');
    }
}
