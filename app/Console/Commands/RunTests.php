<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class RunTests
 * @package App\Console\Commands
 */
class RunTests extends Command
{
    /**
     * @var string
     */
    protected $name = 'test';

    /**
     * @var string
     */
    protected $description = 'Run the tests for the Elements.';

    /**
     * RunTests constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fire the artisan command.
     * @return mixed
     */
    public function fire()
    {
        $filter        = $this->option('filter');
        $commandOutput = $this->runTests($filter);
        $failures      = preg_match('/FAILURES!/', $commandOutput);

        return $this->results($commandOutput, $failures);
    }

    /**
     * Run Phpunit tests for all Elements.
     * @param $filter
     * @return string
     */
    protected function runTests($filter)
    {
        if ($filter) {
            return shell_exec(sprintf('./vendor/bin/phpunit tests/Elements/ --filter=%s', $filter));
        }

        return shell_exec('./vendor/bin/phpunit tests/Elements/');
    }

    /**
     * Display the results.
     * @param $commandOutput
     * @param $failures
     * @return mixed
     */
    protected function results($commandOutput, $failures)
    {
        $this->info($commandOutput);

        return $failures;
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['filter', null, InputOption::VALUE_OPTIONAL, 'Filter through the suite and run specific tests only.', null]
        ];
    }
}
