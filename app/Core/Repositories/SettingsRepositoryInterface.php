<?php
namespace App\Core\Repositories;

interface SettingsRepositoryInterface
{
    public function getSettings($organization_id);

    public function storeSettings($input, $organization);

    public function updateSettings($input, $organization, $settings);
}