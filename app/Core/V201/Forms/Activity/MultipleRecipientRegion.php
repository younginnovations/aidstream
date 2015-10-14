<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleRecipientRegion
 * @package App\Core\V201\Forms\Activity
 */
class MultipleRecipientRegion extends BaseForm
{
    /**
     * builds activity Recipient Region form
     */
    public function buildForm()
    {
        $this
            ->addCollection('recipient_region', 'Activity\RecipientRegion', 'recipient_region')
            ->addAddMoreButton('add_recipient_region', 'recipient_region');
    }
}
