<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ActivityScope
 * @package App\Core\V201\Forms\Activity
 */
class ActivityScope extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds activity scope form
     */
    public function buildForm()
    {
        $this
            ->add(
                'activity_scope',
                'select',
                [
                    'choices' => $this->getCodeList('ActivityScope', 'Activity'),
                    'label'   => 'Activity scope'
                ]
            );
    }
}
