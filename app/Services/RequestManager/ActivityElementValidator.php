<?php namespace App\Services\RequestManager;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ActivityElementValidator
 * @package App\Services\RequestManager
 */
class ActivityElementValidator
{
    /**
     * @var activity element validator instance
     */
    protected $elementValidator;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->elementValidator = $version->getActivityElement()->getActivityElementValidator();
    }

    /**
     * validate activity
     * @param Activity   $activityData
     * @param Collection $transactionData
     * @return
     */
    public function validateActivity(Activity $activityData, Collection $transactionData)
    {
        return $this->elementValidator->validateActivity($activityData, $transactionData);
    }
}

