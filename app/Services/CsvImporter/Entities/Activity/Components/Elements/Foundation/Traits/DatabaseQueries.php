<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Traits;

use App\Models\Activity\Activity;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DatabaseQueries
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Traits
 */
trait DatabaseQueries
{
    /**
     * Get all Activity Identifiers present until now.
     * @return array
     */
    protected function activityIdentifiers()
    {
        $identifiers = [];

        foreach ($this->activities() as $activity) {
            $identifiers[] = getVal($activity->identifier, ['activity_identifier']);
        }

        return $identifiers;
    }

    /**
     * Get all the Activities.
     * @return Collection
     */
    protected function activities()
    {
        return app()->make(Activity::class)->query()->get(['identifier']);
    }
}
