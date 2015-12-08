<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class DefaultTiedStatus
 * @package App\Core\V201\Forms\Activity
 */
class DefaultTiedStatus extends BaseForm
{
    /**
     * builds the Activity Default Tied Status form
     */
    public function buildForm()
    {
        $this
            ->add(
                'default_tied_status',
                'select',
                [
                    'choices'     => $this->getCodeList('TiedStatus', 'Activity'),
                    'empty_value' => 'Select one of the following option :'
                ]
            );
    }
}
