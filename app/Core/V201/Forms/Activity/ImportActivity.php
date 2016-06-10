<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ImportActivity
 * @package App\Core\V201\Forms\Activity
 */
class ImportActivity extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds Import Activity form
     */
    public function buildForm()
    {
        $this
            ->add('activity', 'file', ['label' => 'Activity CSV File', 'wrapper' => ['class' => 'form-group col-xs-6 col-sm-6 activity-file-choose']]);
    }
}
