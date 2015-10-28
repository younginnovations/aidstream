<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleOtherIdentifier
 * @package App\Core\V201\Forms\Activity
 */
class MultipleOtherIdentifier extends BaseForm
{
    /**
     * builds multiple activity description form
     */
    public function buildForm()
    {
        $this
            ->addCollection('other_identifier', 'Activity\OtherIdentifier', 'other_identifier')
            ->addAddMoreButton('add_other_identifier', 'other_identifier');
    }
}
