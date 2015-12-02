<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class Relations
 * @package App\Core\V201\Forms\Settings
 */
class Relations extends BaseForm
{
    /**
     * build related activity form
     */
    public function buildForm()
    {
        $this->addCheckBox('related_activity', 'Related Activity');
    }
}
