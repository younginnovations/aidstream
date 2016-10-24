<?php namespace App\Services\FormCreator\Activity\Result;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class ImportResult
 * @package App\Services\FormCreator\Activity\Result
 */
class ImportResult
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
        $this->formPath    = $version->getActivityElement()->getImportResultForm();
    }

    /**
     * Creates the result csv upload form
     * @param $id
     * @return $this
     */
    public function createForm($id)
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'POST',
                'url'    => route('activity.result.import-csv', [$id])
            ]
        )->add('Upload', 'submit', ['attr' => ['class' => 'btn pull-left']]);
    }
}
