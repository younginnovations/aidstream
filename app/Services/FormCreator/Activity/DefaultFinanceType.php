<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class DefaultFinanceType
 * @package App\Services\FormCreator\Activity
 */
class DefaultFinanceType
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
        $this->formPath    = $version->getActivityElement()->getDefaultFinanceType()->getForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     * return Activity Default Finance Type edit form.
     */
    public function editForm($data, $activityId)
    {
        $model['default_finance_type'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.default-finance-type.update', [$activityId, 0])
            ]
        )->add('Save', 'submit');
    }
}
