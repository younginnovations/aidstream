<?php namespace App\Np\Repositories\Activity;

use App\Np\Contracts\ActivityLocationRepositoryInterface;
use App\Models\Activity\ActivityLocation;

/**
 * Class ActivityRepository
 * @package App\Np\Repositories\NpActivity
 */
class ActivityLocationRepository implements ActivityLocationRepositoryInterface
{
    /**
     * @var ActivityLocation
     */
    protected $activityLocation;

    /**
     * ActivityRepository constructor.
     * @param ActivityLocation $activity
     */
    public function __construct(ActivityLocation $activityLocation)
    {
        $this->activityLocation = $activityLocation;
    }

    public function find($id)
    {
        return $this->activityLocation->where('activity_id', '=', $id)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $data)
    {
        $this->activityLocation->where('activity_id', '=', $data['activity_id'])->delete();
        
        $activityId = $data['activity_id'];

        unset($data['activity_id']);
        foreach ($data as $key => $value) {
            $arr = [];
            if (!isset($value['ward'])) {
                $arr['municipality_id'] = $value['municipality'];
                $arr['activity_id']= $activityId;
                $this->activityLocation->create($arr);
            }else{
                foreach ($value['ward'] as $ward) {
                    $arr['municipality_id'] = $value['municipality'];
                    $arr['ward'] = $ward;
                    $arr['activity_id'] = $activityId;

                    $this->activityLocation->create($arr);
                }
            }
        }
        return true;
    }
}
