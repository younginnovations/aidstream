<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class ImportActivity
 * @package App\Services\FormCreator\Activity
 */
class ImportActivity
{

    protected $formBuilder;
    protected $version;
    protected $formPath;

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = $version->getActivityElement()->getImportActivityForm();
    }

    /**
     * Creates the activity csv upload form
     * @return $this
     */
    public function createForm()
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'POST',
                'url'    => route('activity.import-csv')
            ]
        )->add('Upload', 'submit', ['attr' => ['class' => 'btn pull-left']]);
    }
}
