<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class DocumentLink
 * @package App\Services\FormCreator\Activity
 */
class DocumentLink
{
    /**
     * @var FormBuilder
     */
    protected $formBuilder;
    /**
     * @var Version
     */
    protected $version;
    /**
     * @var
     */
    protected $formPath;

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = $version->getActivityElement()->getDocumentLink()->getForm();
    }


    /**
     * return activity document link form
     * @param      $activityId
     * @param null $data
     * @return $this
     */
    public function getForm($activityId, $data = null)
    {
        $modal['document_link'][0] = $data ? $data->document_link : null;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => route('activity.document-link.update', [$activityId, $data ? $data->id : 0])
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']])
                                 ->add(
                                     'Cancel',
                                     'static',
                                     [
                                         'tag'     => 'a',
                                         'label'   => false,
                                         'value'   => 'Cancel',
                                         'attr'    => [
                                             'class' => 'btn btn-cancel',
                                             'href'  => route('activity.document-link.index', $activityId)
                                         ],
                                         'wrapper' => false
                                     ]
                                 );

    }
}
