<?php namespace App\Http\Controllers;

class LogViewerController extends Controller
{
    function __construct()
    {
        $this->middleware('auth.superAdmin');
    }


    public function index()
    {
      return redirect()->route('show-logs');
    }
}
