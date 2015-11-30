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
            ->addCheckBox('condition', 'Condition')
            ->addCheckBox('result', 'Result')
            ->addCheckBox('legacy_data', 'Legacy Data');
    }
}
