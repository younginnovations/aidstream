<?php namespace App\Http\Controllers\Complete;

use App\Http\Requests\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UpgradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo "";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo "";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo "";
    }

    /**
     * Display upgrade view.
     *
     * @param $version
     * @param $orgId
     * @return \Illuminate\Http\Response
     */
    public function show($version, $orgId)
    {
        return view('Upgrade.show', compact('version', 'orgId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        echo "";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        echo "";
    }
}
