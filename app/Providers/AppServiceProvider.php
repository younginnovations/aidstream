<?php namespace App\Providers;

use App\Core\V201\Requests\Activity\ActivityUploadValidationModifier;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->app['validator']->resolver(function ($translator, $data, $rules, $messages, $attributes) {

			return new ActivityUploadValidationModifier($translator, $data, $rules, $messages, $attributes);

		});
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
		if ($this->app->environment() === 'local') {
//			$this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
		}
	}

}
