@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div><span>Current Username : </span>{{Auth::user()->username}}</div>
                    <div><span>Email : </span>{{Auth::user()->email}}</div>
                </div>
            </div>
            <div class="panel-content-heading panel-title-heading">Change Password</div>
            <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                <div class="create-form create-user-form">
                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ route('user.update-user-password', $user->id)}}">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <div class="col-xs-12 col-md-12">
                                            <label class="control-label">Current Password</label>
                                            <input type="password" class="form-control" name="old_password">
                                            <div><span>Enter your current password.</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <div class="col-xs-12 col-md-12">
                                            <label class="control-label">New Password</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <div class="col-xs-12 col-md-12">
                                            <label class="control-label">Confirm Password</label>
                                            <input type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-form btn-submit">Submit</button>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
