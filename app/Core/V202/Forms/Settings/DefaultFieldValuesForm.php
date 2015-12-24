<?php namespace App\Core\V202\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class DefaultFieldValuesForm
 * @package App\Core\V202\Forms\Settings
 */
class DefaultFieldValuesForm extends BaseForm
{
    /**
     * build default value form
     */
    public function buildForm()
    {
        $this
            ->addSelect('default_currency', $this->getCodeList('Currency', 'Organization'))
            ->addSelect('default_language', $this->getCodeList('Language', 'Organization'))
            ->add('default_hierarchy', 'text')
            ->add('linked_data_uri', 'text', ['label' => 'Linked Data Default'])
            ->addSelect('default_collaboration_type', $this->getCodeList('CollaborationType', 'Organization'))
            ->addSelect('default_flow_type', $this->getCodeList('FlowType', 'Organization'))
            ->addSelect('default_finance_type', $this->getCodeList('FinanceType', 'Organization'))
            ->addSelect('default_aid_type', $this->getCodeList('AidType', 'Organization'))
            ->addSelect('default_tied_status', $this->getCodeList('TiedStatus', 'Organization'))
            ->addSelect('humanitarian', ['1' => 'Yes', '0' => 'No']);
    }
}
