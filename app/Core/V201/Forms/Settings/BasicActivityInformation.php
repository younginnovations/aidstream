<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class BasicActivityInformation
 * @package App\Core\V201\Forms\Settings
 */
class BasicActivityInformation extends BaseForm
{
    /**
     * build basic activity information form
     */
    public function buildForm()
    {
        $this
            ->addCheckBox('title', 'Title', true, 'readonly')
            ->addCheckBox('description', 'Description', true, 'readonly')
            ->addCheckBox('activity_status', 'Activity Status', true, 'readonly')
            ->addCheckBox('activity_date', 'Activity Date', true, 'readonly')
            ->addCheckBox('contact_info', 'Contact Info')
            ->addCheckBox('activity_scope', 'Activity Scope');
    }
}
