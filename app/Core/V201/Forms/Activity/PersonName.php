<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PersonName
 * @package App\Core\V201\Forms\Activity
 */
class PersonName extends BaseForm
{
    /**
     * builds the contact info Person Name form
     */
    public function buildForm()
    {
        $this
            ->addNarrative('personNameNarrative')
            ->addAddMoreButton('add_personNameNarrative', 'personNameNarrative');
    }
}
