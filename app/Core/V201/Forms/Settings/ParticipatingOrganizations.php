<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class ParticipatingOrganizations
 * @package App\Core\V201\Forms\Settings
 */
class ParticipatingOrganizations extends BaseForm
{
    /**
     * build participating organization form
     */
    public function buildForm()
    {
        $this->addCheckBox('participating_organization', 'Participating Organisation', true, 'readonly');
    }
}
