<?php namespace App\Http\Controllers;

use App\Models\Organization\Organization;

/**
 * Class WhoIsUsingController
 * @package App\Http\Controllers
 */
class WhoIsUsingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $organizationCount = Organization::count();

        return view('who-is-using', compact('organizationCount'));
    }

    /**
     * return organization list
     * @param int $page
     * @param int $count
     * @return mixed
     */
    public function listOrganization($page = 0, $count = 20)
    {
        $skip                  = $page * $count;
        $data['next_page']     = Organization::count() > ($skip + $count);
        $data['organizations'] = Organization::select('name', 'logo_url')->skip($skip)->take($count)->get();

        return $data;
    }
}
