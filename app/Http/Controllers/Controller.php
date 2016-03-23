<?php namespace App\Http\Controllers;

use App\Http\Controllers\Complete\Traits\AuthorizesByRequestType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesJobs, ValidatesRequests, AuthorizesRequests, AuthorizesByRequestType;
}
