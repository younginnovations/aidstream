<?php namespace app\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ActivityController
 * @package app\Http\Controllers\Complete\Activity
 */
class ActivityController extends Controller
{
    public function create()
    {
        $this->authorize('add_activity');
        return "add-activity-page";
    }

    /**
     * Throw an unauthorized exception based on gate results.
     *
     * @param  string  $ability
     * @param  array  $arguments
     * @return \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function createGateUnauthorizedException($ability, $arguments)
    {
        return new HttpException(403, 'This action is unauthorized.');
    }
}
