@extends('app')

@section('title', 'Reset User Password - ' . $user->name)

@section('content')
    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
            @include('includes.errors')
            <div class="panel-content-heading">Reset User Password</div>
            <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper user-content-wrapper">
                <div class="create-form create-user-form reset-user-password">
                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ route('admin.update-user-password', $user->id)}}">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-xs-12 col-sm-6">
                                    <label class="control-label">Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <label class="control-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation">
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
