<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Point
 * @package App\Core\V201\Forms\Activity
 */
class Point extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds point form
     */
    public function buildForm()
    {
        $this
            ->add('srs_name', 'text', ['help_block' => $this->addHelpText('Activity_Location_Point-srsName')])
            ->addCollection('position', 'Activity\Position');
    }
}
