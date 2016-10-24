<?php namespace App\Core\V201\Forms\Activity\Result;

use App\Core\Form\BaseForm;

/**
 * Class ImportResult
 * @package App\Core\V201\Forms\Activity\Result
 */
class ImportResult extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds Import Result form
     */
    public function buildForm()
    {
        $this
            ->add('result', 'file', ['label' => 'Result CSV File', 'wrapper' => ['class' => 'form-group col-xs-6 col-sm-6 activity-file-choose']]);
    }
}
