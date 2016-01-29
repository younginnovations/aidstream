<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\Sector as SectorCodeList;

/**
 * Class Sector
 * @package App\Core\V202\Forms\Activity
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
                    'choices'     => $this->getSectorVocabularyCodeList(),
                    'empty_value' => 'Select one of the following option :',
                    'attr'        => ['class' => 'form-control sector_vocabulary']
                ]
            )
            ->add('vocabulary_uri', 'text', ['label' => 'Vocabulary URI'])
            ->add(
                'sector_code',
                'select',
                [
                    'choices'     => $this->getSectorCodeList(),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Sector',
                    'wrapper'     => ['class' => 'form-group hidden sector_types sector_select']
                ]
            )
            ->add(
                'sector_category_code',
                'select',
                [
                    'choices'     => $this->getSectorCategoryCodeList(),
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
                    'wrapper' => ['class' => 'form-group sector_types sector_text']
                ]
            )
            ->addPercentage()
            ->addNarrative('sector_narrative')
            ->addAddMoreButton('add', 'sector_narrative')
            ->addRemoveThisButton('remove');
    }
}
