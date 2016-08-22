@extends('settings.settings')
@section('panel-body')
    <div class="panel-body">
        <div class="create-form settings-form">
            <div class="settings-checkall-wrapper">
                <h2>Activity Elements Checklist</h2>
                <p>Please check the elements you want to add to your activities. The greyed out checkboxes are required to be filled out in AidStream.</p>
                <div class="form-group">
                    <label><input type="checkbox" class="checkAll"/><span class="check-text">Check All</span></label>
                </div>
            </div>
            {!! form_start($form) !!}
            <div id="activity-elements-checklist">
                {!! form_until($form, 'default_field_groups') !!}
            </div>
            {!!  form_end($form) !!}
        </div>
    </div>
@stop
@section('foot')
    {{--@if(session('first_login'))--}}
    {{--<script src="/js/userOnBoarding.js"></script>--}}
    {{--<script>--}}
    {{--$(window).load(function () {--}}
    {{--UserOnBoarding.settingsTour();--}}
    {{--});--}}
    {{--</script>--}}
    {{--@endif--}}
@endsection