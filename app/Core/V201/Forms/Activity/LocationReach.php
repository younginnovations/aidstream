<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class LocationReach
 * @package App\Core\V201\Forms\Activity
 */
class LocationReach extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds location reach form
     */
    public function buildForm()
    {
        $this
            ->add(
                'code',
                'select',
                [
                    'choices' => $this->addCodeList('GeographicLocationReach', 'Activity'),
                ]
            );
    }
}
