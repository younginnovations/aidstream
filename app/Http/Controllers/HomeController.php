<?php namespace App\Http\Controllers;

use App\Models\Organization\Organization;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @var WhoIsUsingController
     */
    protected $organizationCount;

    function __construct(WhoIsUsingController $organizationCount)
    {
        $this->organizationCount = $organizationCount;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $organizationCount = $this->organizationCount->organizationQueryBuilder()->get()->count();

        return view('home', compact('organizationCount'));
    }
}
