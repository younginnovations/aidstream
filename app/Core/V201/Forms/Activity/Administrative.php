<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Administrative
 * @package App\Core\V201\Forms\Activity
 */
class Administrative extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds administrative form
     */
    public function buildForm()
    {
        $this
            ->addSelect('vocabulary', $this->getCodeList('GeographicVocabulary', 'Activity'), trans('elementForm.vocabulary'), $this->addHelpText('Activity_Location_Administrative-vocabulary'))
            ->add('code', 'text', ['label' => trans('elementForm.code'),'label' => trans('elementForm.code'), 'help_block' => $this->addHelpText('Activity_Location_Administrative-code')])
            ->add('level', 'text', ['label' => trans('elementForm.level'), 'help_block' => $this->addHelpText('Activity_Location_Administrative-level')])
            ->addRemoveThisButton('remove');
    }
}
