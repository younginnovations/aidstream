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
                'sector_code',
                'select',
                [
                    'choices'     => $this->getSectorCodeList(),
                    'empty_value' => 'Select one of the following option :',
                    'attr'        => ['class' => 'form-control sector_code']
                ]
            )
            ->add(
                'sector_vocabulary',
                'select',
                [
                    'choices'     => $this->getSectorVocabularyCodeList(),
                    'empty_value' => 'Select one of the following option :',
                    'attr'        => ['class' => 'form-control sector_vocabulary']
                ]
            )
            ->addNarrative('sector_narrative')
            ->addAddMoreButton('add', 'sector_narrative');
    }
}
