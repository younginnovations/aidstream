<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class LegacyDatas
 * @package App\Core\V201\Forms\Activity
 */
class LegacyDatas extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('legacy_data', 'Activity\LegacyData', 'related_activity')
            ->addAddMoreButton('add_legacy_data', 'legacy_data');
    }
}
