<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class UploadTransaction
 * @package App\Services\FormCreator\Activity
 */
class UploadTransaction
{

    protected $formBuilder;
    protected $version;
    protected $formPath;

    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = $version->getActivityElement()->getUploadTransaction()->getForm();
    }

    public function createForm($activityId)
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'POST',
                'url'    => route('activity.transaction-upload.store', [$activityId])
            ]
        )->add('Upload', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
