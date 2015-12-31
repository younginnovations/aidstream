<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\RecipientCountry as RecipientCountryCodeList;

/**
 * Class RecipientCountry
 * @package App\Core\V201\Forms\Activity
 */
class RecipientCountry extends BaseForm
{
    use RecipientCountryCodeList;
    protected $showFieldErrors = true;

    /**
     * builds recipient country form
     */
    public function buildForm()
    {
        $this
            ->addSelect('country_code', $this->getCountryCodeList(), 'Country Code', $this->addHelpText('Activity_Transaction_RecipientCountry-code'))
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
