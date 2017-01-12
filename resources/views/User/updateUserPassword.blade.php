@extends('app')

@section('title', trans('title.change_password'). ' - ' . $user->first_name)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @if (count($errors) == 0)
                    @include('includes.response')
                @endif
                @include('includes.errors')
                <div class="element-panel-heading">
                    <div>@lang('title.change_password')</div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper profile-content-wrapper change-password-wrapper">
                    <div class="create-form create-user-form">
                        <form method="POST"
                              action="{{ route('user.update-user-password', $user->id)}}">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                            <label class="control-label">@lang('user.current_password')</label>
                                            <input type="password" class="form-control" name="old_password">
                                            <div class="description">@lang('user.enter_your_current_password')</div>
                                    </div>
                                    <div class="form-password-group">
                                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                                <label class="control-label">@lang('user.new_password')</label>
                                                <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                                <label class="control-label">@lang('user.confirm_password')</label>
                                                <input type="password" class="form-control"
                                                       name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-form btn-submit">@lang('global.submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
