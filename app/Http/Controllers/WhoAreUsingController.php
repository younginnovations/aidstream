<?php namespace App\Http\Controllers;

/**
 * Class WhoAreUsingController
 * @package App\Http\Controllers
 */
class WhoAreUsingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('who-are-using');
    }
}
