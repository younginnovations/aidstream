<?php namespace App\Core\V203\Forms\Activity\Transactions;

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
                    'empty_value' => trans('elementForm.select_text'),
                    'attr'        => ['class' => 'form-control sector_vocabulary'],
                    'label'       => trans('elementForm.sector_vocabulary')
                ]
            )
            ->add('vocabulary_uri', 'text', ['label' => trans('elementForm.vocabulary_uri')])
            ->add(
                'sector_code',
                'select',
                [
                    'choices'     => $this->getSectorCodeList(),
                    'empty_value' => trans('elementForm.select_text'),
                    'label'       => trans('elementForm.sector_code'),
                    'wrapper'     => ['class' => 'form-group hidden sector_types sector_select']
                ]
            )
            ->add(
                'sector_category_code',
                'select',
                [
                    'choices'     => $this->getSectorCategoryCodeList(),
                    'empty_value' => trans('elementForm.select_text'),
                    'label'       => trans('elementForm.sector_code'),
                    'wrapper'     => ['class' => 'form-group hidden sector_types sector_category_select']
                ]
            )
            ->add(
                'sector_text',
                'text',
                [
                    'label'   => trans('elementForm.sector_code'),
                    'wrapper' => ['class' => 'form-group sector_types sector_text']
                ]
            )
            ->addNarrative('sector_narrative')
            ->addAddMoreButton('add', 'sector_narrative');
    }
}
