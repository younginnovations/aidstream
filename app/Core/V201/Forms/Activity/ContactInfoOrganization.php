<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ContactInfoOrganization
 * @package App\Core\V201\Forms\Activity
 */
class ContactInfoOrganization extends BaseForm
{
    /**
     * builds the contact info organization form
     */
    public function buildForm()
    {
        $this
            ->getNarrative('title')
            ->addAddMoreButton('add_title', 'title');
    }
}
