<?php namespace App\Core\V202\Forms\Organization;

use App\Core\Form\BaseForm;

/**
 * Class TotalExpenditures
 * @package App\Core\V202\Forms\Organization
 */
class TotalExpenditures extends BaseForm
{
    /**
     * build organization Total Expenditures form
     */
    public function buildForm()
    {
        $this
            ->addCollection('total_expenditure', 'Organization\TotalExpenditure', 'total_expenditure')
            ->addAddMoreButton('add', 'total_expenditure');
    }
}
