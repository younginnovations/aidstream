@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="panel-content-heading">User Information</div>
                    <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">
                        <div class="create-form">
                            <form class="form-horizontal" role="form" method="POST"
                              action="{{ route('admin.signup-user')}}">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        @if (count($errors) > 0)
                                            <div class="alert alert-danger">
                                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">First Name</label>

                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="first_name"
                                                       value="{{ old('first_name') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Last Name</label>

                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="last_name"
                                                       value="{{ old('last_name') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">E-Mail Address</label>

                                            <div class="col-md-6">
                                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Username</label>

                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="username"
                                                       value="{{ old('username') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Password</label>

                                            <div class="col-md-6">
                                                <input type="password" class="form-control" name="password">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Confirm Password</label>

                                            <div class="col-md-6">
                                                <input type="password" class="form-control" name="password_confirmation">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">User permission</div>
                                    <div class="panel-body">
                                        <label><input type="checkbox" class="hidden checkAll"/><span class="btn btn-primary">Check All</span></label>
                                        <div class="form-group col-md-12">
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="add_activity" name=user_permission[add] class="field1" @if(isset($data['user_permission']['add'])) checked="checked" @endif >Add</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="edit_activity" name=user_permission[edit] class="field1" @if(isset($data['user_permission']['edit'])) checked="checked" @endif >Edit</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="delete_activity" name=user_permission[delete] class="field1" @if(isset($data['user_permission']['delete'])) checked="checked" @endif >Delete</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="publish_activity" name=user_permission[publish] class="field1" @if(isset($data['user_permission']['publish'])) checked="checked" @endif >Publish</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Sign Up
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
