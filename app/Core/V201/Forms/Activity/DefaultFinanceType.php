<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class DefaultFinanceType
 * @package App\Core\V201\Forms\Activity
 */
class DefaultFinanceType extends BaseForm
{
    /**
     * builds the Activity Default Finance Type form
     */
    public function buildForm()
    {
        $this->addSelect('default_finance_type', $this->getCodeList('FinanceType', 'Activity'), 'Default Finance Type', $this->addHelpText('Activity_DefaultFinanceType-code'), null, true);
    }
}
