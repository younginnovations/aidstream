@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">User Profile</div>
                    <div class="panel-body">
                        <div class="col-md-4">
                            Name:
                        </div>
                        <div class="col-md-8">
                            {{$userProfile->name}}
                        </div>
                        <div class="col-md-4">
                            User Name:
                        </div>
                        <div class="col-md-8">
                            {{$userProfile->username}}
                        </div>
                        <div class="col-md-4">
                            Email:
                        </div>
                        <div class="col-md-8">
                            {{$userProfile->email}}
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary" href="{{route('admin.reset-user-password', $userProfile->id)}}">Reset Password</a>
                <a class="btn btn-primary" href="{{route('admin.edit-user-permission', $userProfile->id)}}">Edit User Permission</a>
            </div>
            @include('includes.side_bar_menu')
        </div>
    </div>
@stop
