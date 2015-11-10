<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class DefaultAidType
 * @package App\Core\V201\Forms\Activity
 */
class DefaultAidType extends BaseForm
{
    /**
     * builds the Activity Default Aid Type form
     */
    public function buildForm()
    {
        $this
            ->add(
                'default_aid_type',
                'select',
                [
                    'choices' => $this->getCodeList('AidType', 'Activity'),
                ]
            );
    }
}
