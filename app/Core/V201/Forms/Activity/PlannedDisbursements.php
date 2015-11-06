<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PlannedDisbursements
 * @package App\Core\V201\Forms\Activity
 */
class PlannedDisbursements extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('planned_disbursement', 'Activity\PlannedDisbursement', 'planned_disbursement')
            ->addAddMoreButton('add_planned_disbursement', 'planned_disbursement');
    }
}
