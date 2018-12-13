@extends('np.base.base')

@section('title', trans('lite/title.edit_profile'))

@section('content')
    {{Session::get('message')}}

    <div class="col-xs-9 col-lg-9 content-wrapper activity-wrapper">
        @include('includes.response')
        <div id="xml-import-status-placeholder"></div>
        <div class="panel panel-default">
            <div class="panel__heading">
                <div class="panel__title">@lang('lite/title.edit_profile')</div>
            </div>
            <div class="panel__body">
                <div class="create-form">
                    {!! form_start($form) !!}
                    <div class="form__block">
                        <div class="col-md-9">
                            <h2>@lang('lite/global.personal_information')</h2>
                            <div class="row">
                                {!! form_until($form, 'timeZone') !!}
                            </div>
                            <div class="form-group col-sm-6 upload-logo-block edit-profile-block edit-profile-form-block">
                                <label class="control-label">@lang('lite/profile.profile_picture')</label>
                                <div class="upload-logo">
                                    {{ Form::file('profile_picture',['class'=>'inputfile form-control', 'id' => 'picture']) }}
                                    <label for="file-logo">
                                        <div class="uploaded-logo">
                                            @if($loggedInUser->profile_picture)
                                                <img src="{{ $loggedInUser->profile_url }}" height="150" width="150"
                                                     alt="{{ $loggedInUser->profile_picture }}" id="selected_picture">
                                            @else
                                                <img src="" height="150" width="150" alt="Uploaded Image"
                                                     id="selected_picture">
                                            @endif
                                            <div class="change-logo-wrap">
                                                <span class="change-logo">@lang('user.change_picture')</span>
                                            </div>
                                        </div>
                                    </label>
                                    <span class="upload-label">@lang("user.upload_picture")</span>
                                </div>
                                <div class="description">
                                    <span>@lang('global.image_criteria')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form__block">
                        <div class="col-md-9">
                            <h2>@lang('lite/global.organisation_information')</h2>
                            <div class="row">
                                {!! form_until($form, 'secondaryEmail') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        {!! form_rest($form) !!}
                    </div>
                    {!! form_end($form) !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{url('js/chunk.js')}}"></script>
    <script>
        Chunk.displayPicture();
        Chunk.usernameGenerator();
    </script>
@stop
