<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class RelatedDocuments
 * @package App\Core\V201\Forms\Settings
 */
class RelatedDocuments extends BaseForm
{
    /**
     * build related document form
     */
    public function buildForm()
    {
        $this->addCheckBox('document_link', trans('element.document_link'));
    }
}
