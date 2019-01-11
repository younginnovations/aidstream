<?php 
namespace App\Core\V203\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class DefaultAidType
 * @package App\Core\V203\Forms\Activity
 */
class DefaultAidType extends BaseForm
{
    /**
     * builds the Activity Default Aid Type form
     */
    public function buildForm()
    {
        $this
        ->addCollection('aid_type', 'Activity\Transactions\AidType','aid_type')
        ->addAddMoreButton('add', 'aid_type');
    }
}
