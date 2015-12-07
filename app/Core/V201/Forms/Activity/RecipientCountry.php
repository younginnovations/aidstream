<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class RecipientCountry
 * @package App\Core\V201\Forms\Activity
 */
class RecipientCountry extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds recipient country form
     */
    public function buildForm()
    {
        $this
            ->add(
                'country_code',
                'select',
                [
                    'choices'     => $this->getCodeList('Country', 'Organization'),
                    'empty_value' => 'Select one of the following option :'
                ]
            )
            ->addPercentage()
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_recipient_country');
    }
}
