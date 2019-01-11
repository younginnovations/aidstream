<?php 
namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Sector
 * @package App\Core\V203\Forms\Activity
 */
class Tags extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('tag', 'Activity\Tag', 'tag')
            ->addAddMoreButton('add', 'tag');
    }
    
}
