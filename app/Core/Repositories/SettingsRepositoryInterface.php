<?php
namespace App\Core\Repositories;

interface SettingsRepositoryInterface
{
    public function getSettings($id);

    public function updateSettings($input, $id);
}