@extends('settings.settings')
@section('panel-body')
    <div class="panel-body">
        <div class="create-form settings-form">
            <div class="settings-checkall-wrapper">
                <h2>@lang('setting.default_values')</h2>
                <p>@lang('setting.these_values_will_be_published')</p>
            </div>
            <div id="default_values">
                {!! form_start($form) !!}
                <h2>@lang('setting.default_for_all_data')</h2>
                <div class="col-md-12">
                    {!! form_until($form, 'default_language') !!}
                </div>
                <h2>@lang('setting.default_for_activity_data')</h2>
                <div class="col-md-12">
                    {!! form_until($form, 'linked_data_uri') !!}
                </div>
                <div class="col-md-12">
                    {!! form_until($form, 'default_flow_type') !!}
                </div>
                <div class="col-md-12">
                    {!! form_until($form, 'default_aid_type') !!}
                </div>
                <div class="col-md-12">
                    {!! form_until($form,'default_tied_status') !!}
                </div>
                @if(Session::get('version') != 'V201')
                    <div class="col-md-12">
                        {!! form_until($form, 'humanitarian') !!}
                    </div>
                @endif
                {!!  form_end($form) !!}
            </div>
        </div>
    </div>
@stop
@section('foot')
    <script>
        $(window).load(function () {
            @if(session('first_login') && auth()->user()->isAdmin())
                var stepNumber = location.hash.replace('#', '');
                if (stepNumber == 5) {
                    var completedSteps = [{!! json_encode((array)$completedSteps) !!}];
                    UserOnBoarding.getLocalisedSettingsText(completedSteps);
                }
            @endif
            UserOnBoarding.validateDefaultValues();
        });
    </script>
@endsection
