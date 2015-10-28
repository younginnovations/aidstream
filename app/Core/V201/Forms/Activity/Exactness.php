<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Exactness
 * @package App\Core\V201\Forms\Activity
 */
class Exactness extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds exactness form
     */
    public function buildForm()
    {
        $this
            ->add(
                'code',
                'select',
                [
                    'choices' => $this->addCodeList('GeographicExactness', 'Activity'),
                ]
            );
    }
}
