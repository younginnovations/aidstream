@extends('np.base.base')

@section('title', trans('lite/title.settings'))

@section('content')
    {{Session::get('message')}}

    <div class="col-xs-9 col-lg-9 content-wrapper activity-wrapper">
        @include('includes.response')
        <div id="xml-import-status-placeholder"></div>
        <div class="panel panel-default">
            <div class="panel__heading">
                <div>
                    <div class="panel__title">@lang('lite/title.settings')</div>
                    {{--
                        @if ($loggedInUser->isAdmin() && session('version') == 'V202')
                        <button class="btn btn-sm pull-right" data-toggle="modal"
                                data-target="#system-upgrade-modal">@lang('lite/settings.version_upgrade')</button>
                    @endif
                    --}}
                </div>
            </div>
            <div class="panel__body">
                <div class="create-form user-form settings-lite-form">
                    <div class="row">
                        {!! form_start($form) !!}
                        <div class="col-md-9">
                            {!! form_until($form, 'apiKey') !!}
                            <div class="form-group col-sm-12">
                            <p>@lang('setting.contact_us')</p>
                            </div>
                            {!! form_until($form, 'defaultLanguage') !!}
                            <div class="form-group upload-logo-block edit-profile-block edit-profile-form-block">
                                <label class="control-label">Profile Picture</label>
                                <div class="upload-logo">
                                    {{ Form::file('organisation_logo',['class'=>'inputfile form-control', 'id' => 'picture', 'old' => old('organisation_logo')]) }}
                                    <label for="file-logo">
                                        <div class="uploaded-logo">
                                            @if($loggedInUser->organization->logo)
                                                <img src="{{ url($loggedInUser->organization->logo_url) }}" height="150" width="150"
                                                     alt="{{ $loggedInUser->organization->logo }}" id="selected_picture">
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
                        <div class="border-btn-line">
                            {!! form_rest($form) !!}
                        </div>
                        {!! form_end($form) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="system-upgrade-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="pull-left">
                    <img src="{{ asset('images/ic-lite-logo.svg') }}"/> <span class="glyphicon glyphicon-arrow-right"></span> <img src="{{ asset('images/ic-core-logo.svg') }}"/>
                    </div>
                    <h4 class="modal-title" id="myModalLabel"><b>@lang('lite/settings.confirm_upgrade')</b></h4>
                </div>

                <form action="{{ route('lite.settings.upgrade-version') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>
                            <b>@lang('lite/settings.upgrade_changes.first')</b>
                        </p>
                        <p>
                            <ul>
                                <li>
                                    @lang('lite/settings.upgrade_changes.second')
                                </li>
                                <li>
                                    @lang('lite/settings.upgrade_changes.third')
                                </li>
                            </ul>
                        </p>
                        <p>
                            @lang('lite/settings.upgrade_changes.unsure')
                        </p>
                        <li>
                            @lang('lite/settings.upgrade_changes.recommendations-wiki')
                        </li>
                        <li>
                            @lang('lite/settings.upgrade_changes.recommendations-support')
                        </li>
                    </div>
                    <div class="modal-footer">
                        <label>
                            <input type="checkbox" id="agree-upgrade">@lang('lite/settings.agree_upgrade')
                        </label>
                        <button type="submit" disabled id="submit-upgrade"
                                class="btn btn-primary">@lang('lite/settings.version_upgrade')</button>
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">@lang('lite/global.cancel')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(session('status'))
        @include('lite.settings.usernameUpdated')
    @endif
@stop

@section('script')
    <script>
        var agencies = {!! $agencies !!};
        var selectedRegistrationAgency = "{!! $registrationAgency !!}";
        var country = "{!! $country !!}";
    </script>
    <script>
    $(document).ready(function(){
        var country = $('.country > select').first();
        var options = country.find('option');
        options.remove();
        country.append("<option value='NP'>NP - NEPAL</option>");

    })
    </script>
    <script src="{{ asset('np/js/settings.js') }}"></script>
    <script src="{{ asset('js/chunk.js') }}"></script>
    <script>
        @if(session('status'))
                $('#usernameChanged').modal({
            backdrop: 'static',
            keyboard: false
        });
        @endif
        Chunk.displayPicture();
        $(document).ready(function () {
            $('#agree-upgrade').change(function () {
                if (this.checked) {
                    $('#submit-upgrade').attr('disabled', false);
                } else {
                    $('#submit-upgrade').attr('disabled', true);
                }
            });
        });
    </script>
    <script>
      $(document).ready(function(){
        var registrationAgency = $('.organization_registration_agency > select').val()
        if(registrationAgency != 'NP-DAO'){
            $('.district').hide();
        }
    })
    </script>
@stop
