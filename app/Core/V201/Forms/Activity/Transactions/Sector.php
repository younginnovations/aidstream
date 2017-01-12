<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\Sector as SectorCodeList;

/**
 * Class Sector
 * @package App\Core\V201\Forms\Activity
 */
class Sector extends BaseForm
{
    use SectorCodeList;

    /**
     * builds the activity sector form
     */
    public function buildForm()
    {
        $this
            ->add(
                'sector_vocabulary',
                'select',
                [
                    'label'       => trans('elementForm.sector_vocabulary'),
                    'choices'     => $this->getSectorVocabularyCodeList(),
                    'empty_value' => trans('elementForm.select_text'),
                    'attr'        => ['class' => 'form-control sector_vocabulary'],
                    'help_block'  => $this->addHelpText('Activity_Transaction_Sector-vocabulary')
                ]
            )
            ->add(
                'sector_code',
                'select',
                [
                    'choices'     => $this->getSectorCodeList(),
                    'empty_value' => trans('elementForm.select_text'),
                    'label'       => trans('elementForm.sector_code'),
                    'wrapper'     => ['class' => 'form-group hidden sector_types sector_select'],
                    'help_block'  => $this->addHelpText('Activity_Sector-code')
                ]
            )
            ->add(
                'sector_category_code',
                'select',
                [
                    'choices'     => $this->getSectorCategoryCodeList(),
                    'empty_value' => trans('elementForm.select_text'),
                    'label'       => trans('elementForm.sector_code'),
                    'wrapper'     => ['class' => 'form-group hidden sector_types sector_category_select'],
                    'help_block'  => $this->addHelpText('Activity_Sector-dac_three_code')
                ]
            )
            ->add(
                'sector_text',
                'text',
                [
                    'label'      => trans('elementForm.sector_code'),
                    'wrapper'    => ['class' => 'form-group sector_types sector_text'],
                    'help_block' => $this->addHelpText('Activity_Sector-non_dac_code')
                ]
            )
            ->addNarrative('sector_narrative')
            ->addAddMoreButton('add', 'sector_narrative');
    }
}
