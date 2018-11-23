<?php namespace App\Np\Contracts;

use App\Models\Activity\ActivityLocation;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ActivityRepositoryInterface
 * @package App\Np\Contracts
 */
interface ActivityLocationRepositoryInterface
{
    /**
     * Save the Activity data into the database.
     *
     * @param array $data
     * @return Activity
     */
    public function save(array $data);

}
