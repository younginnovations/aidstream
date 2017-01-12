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
            ->add(
                'srs_name',
                'text',
                [
                    'label'      => trans('elementForm.srs_name'),
                    'help_block' => $this->addHelpText('Activity_Location_Point-srsName'),
                    'attr'       => ['readonly' => true],
                    'value'      => 'http://www.opengis.net/def/crs/EPSG/0/4326'
                ]
            )
            ->addCollection('position', 'Activity\Position', '', [], trans('elementForm.position'));
    }
}
