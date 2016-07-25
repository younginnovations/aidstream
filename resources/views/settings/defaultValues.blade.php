@extends('settings.settings')
@section('panel-body')
    <div class="panel-body">
        <div class="create-form settings-form">
            <h2>Default Values</h2>
            <p>These values will be used in the xml files which is published to the IATI Registry. You have the option to override the activities.</p>
            <hr/>
            <h2>Default for all data</h2>
            <div id="default_values">
                {!! form_start($form) !!}
                <div class="col-md-12">
                    {!! form_until($form, 'default_language') !!}
                </div>
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
                <div class="col-md-12">
                    {!!  form_end($form) !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('foot')
    {{--<script src="/js/userOnBoarding.js"></script>--}}
    {{--<script>--}}
        {{--$(window).load(function () {--}}
            {{--@if(session('first_login'))--}}
                {{--UserOnBoarding.settingsTour();--}}
            {{--@endif--}}
            {{--UserOnBoarding.validateDefaultValues();--}}
        {{--});--}}
    {{--</script>--}}
@endsection
