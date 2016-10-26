<?php
namespace App\Core\Repositories;

interface SettingsRepositoryInterface
{
    public function getSettings($organization_id);
}