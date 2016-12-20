<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class Performance
 * @package App\Core\V201\Forms\Settings
 */
class Performance extends BaseForm
{
    /**
     * build performance form
     */
    public function buildForm()
    {
        $this
            ->addCheckBox('condition', trans('element.condition'))
            ->addCheckBox('result', trans('element.result'))
            ->addCheckBox('legacy_data', trans('element.legacy_data'));
    }
}
