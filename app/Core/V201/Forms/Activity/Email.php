<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Email
 * @package App\Core\V201\Forms\Activity
 */
class Email extends BaseForm
{
    /**
     * builds the contact info email form
     */
    public function buildForm()
    {
        $this
            ->add('email', 'text', ['help_block' => $this->addHelpText('Activity_ContactInfo_Email-text')])
            ->addRemoveThisButton('remove_email');
    }
}
