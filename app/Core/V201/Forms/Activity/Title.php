<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Title
 * Contains the function to create the title form
 * @package App\Core\V201\Forms\Activity
 */
class Title extends BaseForm
{
    /**
     * builds the activity title form
     */
    public function buildForm()
    {
        $class = $this->getData('class') ? $this->getData('class') : 'narrative';
        $bool  = ($this->getData('narrative_true') || !$this->getName()) ? true : false;
        $this
            ->addData(['narrative_required' => $bool])
            ->addNarrative($class)
            ->addAddMoreButton('add_title', $class);
    }
}
