<?php namespace Test;

use Illuminate\Foundation\Testing\TestCase;

/**
 * Class AidstreamTestCase
 * @package Test
 */
abstract class AidstreamTestCase extends TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

}
