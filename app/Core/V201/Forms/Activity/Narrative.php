<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Narrative
 * @package App\Core\V201\Forms\Activity
 */
class Narrative extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds the narrative form
     */
    public function buildForm()
    {
        $this
            ->add('narrative', 'text', ['label' => $this->getData('label')])
            ->addSelect('language', $this->getCodeList('Language', 'Activity'))
            ->addRemoveThisButton('remove_from_collection');
    }
}
