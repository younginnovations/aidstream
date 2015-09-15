<?php namespace App\Http\Controllers\Complete;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;

class AdminController extends Controller
{
    public function index()
    {
        $activity = UserActivity::all();

        return view('admin.activityLog',compact('activity'));
    }
}