<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PolicyMakers
 * @package App\Core\V201\Forms\Activity
 */
class PolicyMakers extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('policy_maker', 'Activity\PolicyMaker', 'policy_maker')
            ->addAddMoreButton('add_policy_maker', 'policy_maker');
    }
}
