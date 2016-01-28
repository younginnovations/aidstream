@extends('app')

@section('title', 'User Profile - ' . $userProfile->name)

@section('content')
    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper user-wrapper">
                <div class="panel-content-heading">User Profile</div>
                <div class="panel panel-default panel-element-detail panel-user-view">
                    <div class="panel-element-body">
                        <div class="col-md-12">
                            <div class="col-xs-12 col-md-4">Name:</div>
                            <div class="col-xs-12 col-md-8">{{$userProfile->name}}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-xs-12 col-md-4">User Name:</div>
                            <div class="col-xs-12 col-md-8">{{$userProfile->username}}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-xs-12 col-md-4">Email:</div>
                            <div class="col-xs-12 col-md-8">{{$userProfile->email}}</div>
                        </div>
                    </div>
                <a class="btn btn-primary btn-form btn-submit" href="{{route('admin.reset-user-password', $userProfile->id)}}">Reset Password</a>
                <a class="btn btn-primary btn-form btn-submit" href="{{route('admin.edit-user-permission', $userProfile->id)}}">Edit User Permission</a>
              </div>
            </div>
        </div>
    </div>
@stop
