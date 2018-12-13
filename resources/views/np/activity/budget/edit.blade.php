@extends('np.base.base')

@section('title', trans('lite/title.budget'))

@section('content')
    {{Session::get('message')}}

    <div class="col-xs-9 col-lg-9 content-wrapper activity-wrapper">
        @include('includes.response')
        <div id="xml-import-status-placeholder"></div>
        <div class="panel panel-default">
            <div class="panel__heading">
                <div class="panel__title">@lang('lite/title.budget')</div>
            </div>
            <div class="panel__body">
                <div class="create-form user-form">
                    <div class="row">
                        {!! form_start($form) !!}
                        <div class="col-md-9">
                            {!! form_until($form,'add_more_budget') !!}
                        </div>
                        <div class="border-btn-line">
                            {!! form_rest($form) !!}
                                <a class='btn btn-form' style="margin-left:0;padding-top:15px;" href="{{route('np.activity.show', $activityId)}}">@lang('lite/global.cancel')</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="budget-container hidden" data-prototype="{{ form_row($form->budget->prototype()) }}"></div>
        </div>
    </div>
@stop
@section('script')
    <script type="text/javascript" src="{{ url('/lite/js/createActivity.js') }}"></script>
    <script type="text/javascript">
        CreateActivity.addToCollection();
    </script>
@endsection
