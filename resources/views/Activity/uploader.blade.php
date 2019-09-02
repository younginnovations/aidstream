@extends('app')

@section('title', trans('title.upload_activities'))

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper upload-activity-wrapper">
                @include('includes.response')
                @if (isset($mismatch))
                <div class="alert alert-{{$mismatch['type']}}">
                    <span>{!! message($mismatch) !!}</span>
                    </div>
                @endif
                <div id="import-status-placeholder"></div>
                <div class="element-panel-heading">
                    <div>
                        @lang('title.import_activities')
                    </div>
                    <div>
                        <a href="{{ route('activity.index') }}" class="pull-right back-to-list">
                            <span class="glyphicon glyphicon-triangle-left"></span>@lang('global.back_to_activity_list')
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper element-upload-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                            <div class="download-transaction-wrap">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">@lang('global.download_activity_template')
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{route('activity.download-template',['type'=>'basic'])}}">@lang('global.activity_basics')</a></li>
                                        <li><a href="{{route('activity.download-template',['type'=>'transaction'])}}">@lang('global.activity_with_transactions')</a></li>
                                        <li><a href="{{route('activity.download-template',['type'=>'others'])}}">@lang('global.activity_other_fields')</a></li>
                                        <li><a href="{{route('activity.download-template',['type'=>'others-transaction'])}}">@lang('global.activity_with_transaction_other_fields')</a></li>
                                    </ul>
                                </div>
                                <div>
                                    @lang('global.activity_csv_template_text').
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script>
    @if (isset($importing))
        var importing = true;
    @else
        var importing = false;
    @endif
    </script>
    <script>
        var checkSessionRoute = '{{ route('activity.check-session-status')}}';
    </script>
    <script src=" {{ asset('js/csvImporter/checkSessionStatus.js') }}"></script>
@stop
