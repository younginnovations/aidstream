<?php namespace App\Core\V201\Forms\Activity;

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
                    'choices'       => $this->getSectorVocabularyCodeList(),
                    'empty_value'   => 'Select one of the following option :',
                    'default_value' => '1',
                    'attr'          => ['class' => 'form-control sector_vocabulary'],
                    'help_block'    => $this->addHelpText('Activity_Sector-vocabulary')
                ]
            )
            ->add(
                'sector_code',
                'select',
                [
                    'choices'     => $this->getSectorCodeList(),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Sector',
                    'wrapper'     => ['class' => 'form-group hidden sector_types sector_select'],
                    'help_block'  => $this->addHelpText('Activity_Sector-code'),
                    'required'    => true
                ]
            )
            ->add(
                'sector_category_code',
                'select',
                [
                    'choices'     => $this->getSectorCategoryCodeList(),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Sector',
                    'wrapper'     => ['class' => 'form-group hidden sector_types sector_category_select'],
                    'help_block'  => $this->addHelpText('Activity_Sector-dac_three_code'),
                    'required'    => true
                ]
            )
            ->add(
                'sector_text',
                'text',
                [
                    'label'      => 'Sector',
                    'wrapper'    => ['class' => 'form-group sector_types sector_text'],
                    'help_block' => $this->addHelpText('Activity_Sector-non_dac_code'),
                    'required'   => true
                ]
            )
            ->addPercentage($this->addHelpText('Activity_Sector-percentage'))
            ->addNarrative('sector_narrative')
            ->addAddMoreButton('add', 'sector_narrative')
            ->addRemoveThisButton('remove');
    }
}
