<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class Identification
 * @package App\Core\V201\Forms\Settings
 */
class Identification extends BaseForm
{
    /**
     * build identification form
     */
    public function buildForm()
    {
        $this->addCheckBox('other_identifier', trans('element.other_identifier'));
    }
}
