<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Sector
 * @package App\Core\V201\Forms\Activity
 */
class Sector extends BaseForm
{
    /**
     * builds the activity sector form
     */
    public function buildForm()
    {
        $this
            ->add(
                'vocabulary',
                'select',
                [
                    'choices'     => $this->getCodeList('SectorVocabulary', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'attr'        => ['class' => 'form-control sector_types sector_vocabulary']
                ]
            )
            ->add(
                'sector_select',
                'select',
                [
                    'choices'     => $this->getCodeList('Sector', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Sector',
                    'wrapper'     => ['class' => 'form-group hidden sector_types sector_select']
                ]
            )
            ->add(
                'sector_category_select',
                'select',
                [
                    'choices'     => $this->getCodeList('SectorCategory', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Sector',
                    'wrapper'     => ['class' => 'form-group hidden sector_types sector_category_select']
                ]
            )
            ->add(
                'sector_text',
                'text',
                [
                    'label'   => 'Sector',
                    'wrapper' => ['class' => 'form-group hidden sector_text']
                ]
            )
            ->addPercentage()
            ->addNarrative('sector_narrative')
            ->addAddMoreButton('add', 'sector_narrative')
            ->addRemoveThisButton('remove');
    }
}
