<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class CountryBudgetItem
 * @package App\Core\V201\Forms\Activity
 */
class CountryBudgetItem extends BaseForm
{
    /**
     * builds the activity country budget item form
     */
    public function buildForm()
    {
        $this
            ->addSelect('vocabulary', $this->getCodeList('BudgetIdentifierVocabulary', 'Activity'), 'Vocabulary', $this->addHelpText('Activity_CountryBudgetItems-vocabulary'))
            ->addCollection('budget_item', 'Activity\BudgetItem', 'budget_item')
            ->addAddMoreButton('add_budget_item', 'budget_item');
    }
}
