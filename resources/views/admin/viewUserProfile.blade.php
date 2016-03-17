@extends('app')

@section('title', 'User Profile - ' . $userProfile->name)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper user-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>User Profile</div>
                </div>
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
                        <div class="col-md-12">
                            <div class="col-xs-12 col-md-4">User permissions:</div>
                            <div class="col-xs-12 col-md-8">
                                <a data-toggle="modal" data-target="#myModal">add permissions</a>
                                <div class="modal fade" id="myModal" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">User Permissions</h4>
                                            </div>
                                            <div class="modal-body">
                                                <label><input type="checkbox" class="checkAll"/><span class="check-text">Check All</span></label>
                                                <div class="panel panel-default panel-user-permission-edit">
                                                    <form class="form-horizontal" role="form" method="POST">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <div class="pull-left panel panel-default panel-user">
                                                            <div class="panel-body">
                                                                <div class="form-group col-md-12">
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox"
                                                                                      class="field1">Add</label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox"
                                                                                      class="field1" >Edit</label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox"
                                                                                      class="field1">Delete</label>
                                                                    </div>
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox"
                                                                                      class="field1">Publish</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="pull-right btn btn-primary btn-submit btn-form">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="user-action-btn">
                        <a class="btn btn-primary btn-form btn-submit"
                           href="{{route('admin.reset-user-password', $userProfile->id)}}">Reset Password</a>
                        {{--<a class="btn btn-primary btn-form btn-submit"--}}
                           {{--href="{{route('admin.edit-user-permission', $userProfile->id)}}">Edit User Permission</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
