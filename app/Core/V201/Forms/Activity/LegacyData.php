<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class LegacyData
 * @package App\Core\V201\Forms\Activity
 */
class LegacyData extends BaseForm
{
    /**
     * builds activity activity date form
     */
    public function buildForm()
    {
        $this
            ->add('name', 'text')
            ->add('value', 'text')
            ->add('iati_equivalent', 'text')
            ->addRemoveThisButton('remove');
    }
}
