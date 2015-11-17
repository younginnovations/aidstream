<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Result;

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
