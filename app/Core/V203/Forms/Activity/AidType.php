<?php
namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Tag
 * @package App\Core\V203\Forms\Activity
 */
class AidType extends BaseForm
{

    public function buildForm()
    {
        $this
            ->add(
            'default_aidtype_vocabulary',
            'select',
            [
                'label'         => trans('elementForm.vocabulary'),
                'choices'       => $this->getCodeList('AidTypeVocabulary', 'Activity'),
                'empty_value'   => trans('elementForm.select_text'),
                'default_value' => '1',
                'attr'          => ['class' => 'form-control default_aidtype_vocabulary'],
                'help_block'    => $this->addHelpText('Activity_DefaultAidTypeVocabulary-code'),
                'required'      => true
            ]
            )
            ->add(
            'default_aid_type',
            'select',
            [
                'choices'       => $this->getCodeList('AidType', 'Activity'),
                'empty_value'   => trans('elementForm.select_text'),
                'label'         => trans('elementForm.default_aid_type'),
                'wrapper'       => ['class' => 'form-group default_aidtypes aidtype_select'],
                'help_block'    => $this->addHelpText('Activity_DefaultAidType-code'),
                'required'      => true
            ]
            )
            ->add(
                'earmarking_category',
                'select',
                [
                    'choices'     => $this->getCodeList('EarmarkingCategory', 'Activity'),
                    'empty_value' => trans('elementForm.select_text'),
                    'label'       => trans('elementForm.default_aid_type'),
                    'wrapper'     => ['class' => 'form-group hidden default_aidtypes aidtype_earmarking_category'],
                    'help_block'  => $this->addHelpText('Activity_DefaultAidType-code'),
                    'required'    => true
                ]
            )
            ->add(
                'default_aid_type_text',
                'select',
                [
                    'choices'     => $this->getCodeList('EarmarkingModality', 'Activity'),
                    'empty_value' => trans('elementForm.select_text'),
                    'label'       => trans('elementForm.default_aid_type'),
                    'wrapper'     => ['class' => 'form-group hidden default_aidtypes aidtype_text earmarking_modality'],
                    'help_block'  => $this->addHelpText('Activity_DefaultAidType-code'),
                    'required'    => true
                ]
            )
            ->addRemoveThisButton('remove_default_aid_type');
    }
}
