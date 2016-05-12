<?php namespace App\Http\Controllers;

use App\Models\Organization\Organization;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    function __construct()
    {
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $organizationCount = Organization::where('display', 1)->get()->count();

        return view('home', compact('organizationCount'));
    }
}
