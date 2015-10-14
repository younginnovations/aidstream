<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleContactInfo
 * @package App\Core\V201\Forms\Activity
 */
class MultipleContactInfo extends BaseForm
{
    /**
     * builds activity Contact Info
     */
    public function buildForm()
    {
        $this
            ->addCollection('contact_info', 'Activity\ContactInfo', 'contactInfo')
            ->addAddMoreButton('add_contactInfo', 'contactInfo');
    }
}
