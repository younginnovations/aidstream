<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class Transaction
 * @package App\Services\FormCreator\Activity
 */
class Transaction
{

    protected $formBuilder;
    protected $version;
    protected $formPath;

    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = $version->getActivityElement()->getTransaction()->getForm();
    }

    public function createForm($activityId)
    {
        return $this->displayForm('POST', sprintf('activity/%d/transaction', $activityId));
    }

    public function editForm($activity, $transactionId, $transactionDetails)
    {
        return $this->displayForm('PUT', route('activity.transaction.update', [$activity->id, $transactionId]), $transactionDetails);
    }

    public function displayForm($method, $url, $data = null)
    {
        $model['transaction'][0] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => $method,
                'model'  => $model,
                'url'    => $url
            ]
        )->add((null !== $data) ? 'Update' : 'Create', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
