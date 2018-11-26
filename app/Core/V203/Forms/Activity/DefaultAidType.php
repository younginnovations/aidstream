<?php 
namespace App\Core\V203\Forms\Activity;

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
        ->addCollection('default_aid_type', 'Activity\AidType','default_aid_type')
        ->addAddMoreButton('add', 'default_aid_type');
    }
}
