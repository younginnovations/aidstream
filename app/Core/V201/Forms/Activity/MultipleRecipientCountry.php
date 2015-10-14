<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleRecipientCountry
 * @package App\Core\V201\Forms\Activity
 */
class MultipleRecipientCountry extends BaseForm
{
    /**
     * builds recipient country form
     */
    public function buildForm()
    {
        $this
            ->addCollection('recipient_country', 'Activity\RecipientCountry', 'recipient_country')
            ->addAddMoreButton('add_recipient_country', 'recipient_country');
    }
}
