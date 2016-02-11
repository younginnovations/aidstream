<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ChangeActivityDefault
 * @package App\Core\V201\Forms\Activity
 */
class ChangeActivityDefault extends BaseForm
{
    /**
     * builds activity default form
     */
    public function buildForm()
    {
        $this
            ->addSelect('default_currency', $this->getCodeList('Currency', 'Organization'), null, $this->addHelpText('activity_defaults-default_currency'))
            ->addSelect('default_language', $this->getCodeList('Language', 'Organization'), null, $this->addHelpText('activity_defaults-default_language'))
            ->add('default_hierarchy', 'text', ['help_block' => $this->addHelpText('activity_defaults-hierarchy')])
            ->add('linked_data_uri', 'text', ['help_block' => $this->addHelpText('activity-linked_data_uri')])
            ->addSelect('humanitarian', ['1' => 'Yes', '0' => 'No'], null, $this->addHelpText('activity_defaults-humanitarian'));
    }
}
