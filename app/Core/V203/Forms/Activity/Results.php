<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V203\Traits\Forms\Result\Result;

/**
 * Class Results
 * @package App\Core\V201\Forms\Activity
 */
class Results extends BaseForm
{

    use Result;

    /**
     * builds the Activity Result form
     */
    public function buildForm()
    {
        $this->addResults();
    }
}
