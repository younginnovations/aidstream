<?php namespace App\Migration\Migrator\Data;

use Illuminate\Database\DatabaseManager;

/**
 * Class Query
 * @package App\Migration\Migrator\Data
 */
abstract class Query
{
    /**
     * @var
     */
    protected $connection;

    /**
     *
     */
    protected function initDBConnection()
    {
        $this->connection = app()->make(DatabaseManager::class)->connection('mysql');
    }
}
