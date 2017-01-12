@extends('app')

@section('title', trans('title.change_username'). ' - ' . Auth::user()->first_name)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.errors')
                <div class="element-panel-heading">
                    <div>@lang('title.change_username')</div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper panel-profile profile-content-wrapper">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('user.update-username', $user->id)}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label">@lang('organisation.organisation_user_identifier')</label>
                            <input type="text" class="form-control noSpace" name="organization_user_identifier" value="{{ old('organization_user_identifier') }}">
                            <div><span>@lang('organisation.your_organisation_user_identifier_prefix')</span></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 username_text">
                        <label class="control-label">@lang('user.username')</label>
                        <em>@('organisation.this_will_be_auto_generated')</em>
                    </div>
                    <div class="col-xs-12 col-sm-6 username_value hidden">
                        <label class="control-label">@lang('user.username')</label>
                        <input type="hidden" class="form-control hover_help_text" name="username" value="{{ old('username') }}" readonly="readonly">
                        <div class="alternate_input">{{ old('username') }}</div>
                        <span class="help-text"
                              title=@lang('organisation.aidstream_will_create_default')
                              data-toggle="tooltip" data-placement="top">
                           @lang('organisation.aidstream_will_create_default')
                        </span>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-form btn-submit">@lang('global.submit')</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

