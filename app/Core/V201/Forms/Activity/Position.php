<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Position
 * @package App\Core\V201\Forms\Activity
 */
class Position extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds position form
     */
    public function buildForm()
    {
        $this
            ->add('latitude', 'text', ['label' => trans('elementForm.latitude'), 'help_block' => $this->addHelpText('Activity_Location_Point_Pos-latitude')])
            ->add('longitude', 'text', ['label' => trans('elementForm.longitude'), 'help_block' => $this->addHelpText('Activity_Location_Point_Pos-longitude')])
            ->add(
                'map',
                'static',
                [
                    'label'   => false,
                    'attr'    => [
                        'class' => 'map_container',
                        'style' => 'height: 400px;'
                    ],
                    'wrapper' => ['class' => 'form-group full-width-wrap']
                ]
            );
    }
}
