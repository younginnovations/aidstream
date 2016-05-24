<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth\Traits\ManagesRouteBySubdomain;
use App\Http\Controllers\Complete\Traits\AuthorizesByRequestType;
use App\Http\Controllers\Complete\Traits\AuthorizesOwnerRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesJobs, ValidatesRequests, AuthorizesRequests, AuthorizesByRequestType, AuthorizesOwnerRequest, ManagesRouteBySubdomain;
}
