<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class UploadActivity
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class UploadActivity extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds Activity upload form
     */
    public function buildForm()
    {
        $this
            ->add('activity', 'file', ['label' => 'Activity Csv File']);
    }
}
