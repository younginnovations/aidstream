<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Administrative
 * @package App\Core\V201\Forms\Activity
 */
class Administrative extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds administrative form
     */
    public function buildForm()
    {
        $this
            ->add(
                'vocabulary',
                'select',
                [
                    'choices'     => $this->getCodeList('GeographicVocabulary', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                ]
            )
            ->add('code', 'text')
            ->add('level', 'text')
            ->addRemoveThisButton('remove');
    }
}
