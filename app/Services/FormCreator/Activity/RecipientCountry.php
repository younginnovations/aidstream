<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class RecipientCountry
 * @package App\Services\FormCreator\Activity
 */
class RecipientCountry
{

    protected $formPath;
    /**
     * @var FormBuilder
     */
    protected $formBuilder;
    /**
     * @var Version
     */
    protected $version;

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formPath    = $version->getActivityElement()->getRecipientCountry()->getForm();
        $this->formBuilder = $formBuilder;
        $this->version     = $version;
    }

    /**
     * @param $data
     * @param $activityId
     * @return $this
     * return recipient country edit form.
     */
    public function editForm($data, $activityId)
    {
        $modal['recipient_country'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => route('activity.recipient-country.update', [$activityId, 0])
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
