<?php namespace App\Np\Contracts;

use App\Models\Settings;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface SettingsRepositoryInterface
 * @package App\Np\Contracts
 */
interface NpSettingsRepositoryInterface
{
    /**
     * Get all the Settings of the current Settings.
     *
     * @param $id
     * @return Collection
     */
    public function all($id);

    /**
     * Find an Settings by its id.
     *
     * @param $id
     * @return Settings
     */
    public function find($id);

    /**
     * Save the Settings data into the database.
     *
     * @param       $id
     * @param array $data
     * @return Settings
     */
    public function save($id, array $data);
}
