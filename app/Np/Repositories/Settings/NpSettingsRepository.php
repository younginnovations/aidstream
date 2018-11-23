<?php namespace App\Np\Repositories\Settings;


use App\Np\Contracts\NpSettingsRepositoryInterface;
use App\Models\Settings;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SettingsRepository
 * @package App\Np\Repositories\Settings
 */
class NpSettingsRepository implements NpSettingsRepositoryInterface
{

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * SettingsRepository constructor.
     *
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Provides settings data from organisation Id
     *
     * @param $id
     * @return mixed
     */
    public function getSettingsWithOrgId($id)
    {
        return $this->settings->where('organization_id', $id)->first();
    }

    /**
     * Stores Settings data
     *
     * @param $settings
     * @param $orgId
     * @return Settings
     */
    public function saveWithOrgId($orgId, array $settings)
    {
        return $this->settings->updateorCreate(['organization_id' => $orgId], $settings);
    }

    /**
     * Get all the Settings of the current Settings.
     *
     * @param $id
     * @return Collection
     */
    public function all($id)
    {
        // TODO: Implement all() method.
    }

    /**
     * Find an Settings by its id.
     *
     * @param $id
     * @return Settings
     */
    public function find($id)
    {
        return $this->settings->where('organization_id', $id)->get();
    }

    /**
     * Save the Settings data into the database.
     *
     * @param       $id
     * @param array $data
     * @return Settings
     */
    public function save($id, array $data)
    {
        // TODO: Implement save() method.
    }
}

