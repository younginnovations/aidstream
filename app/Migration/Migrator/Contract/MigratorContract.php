<?php namespace App\Migration\Migrator\Contract;

/**
 * Interface MigratorContract
 * @package App\Migration\Migrator\Contract
 */
interface MigratorContract
{
    /**
     * Migrate data from old system into the new one.
     * @param $accountIds
     * @return string
     */
    public function migrate(array $accountIds);
}
