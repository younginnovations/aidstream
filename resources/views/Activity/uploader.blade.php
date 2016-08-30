@extends('app')

@section('title', 'Upload Activities')

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
                        Import Activities
                    </div>
                    <div>
                        <a href="{{ route('activity.index') }}" class="pull-right back-to-list">
                            <span class="glyphicon glyphicon-triangle-left"></span>Back to Activity List
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
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Download Activity Template
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{route('activity.download-template',['type'=>'basic'])}}">Activity Basics</a></li>
                                        <li><a href="{{route('activity.download-template',['type'=>'transaction'])}}">Activity with Transactions</a></li>
                                        <li><a href="{{route('activity.download-template',['type'=>'others'])}}">Activity with Other Fields</a></li>
                                        <li><a href="{{route('activity.download-template',['type'=>'others-transaction'])}}">Activity with Transactions and Other Fields</a></li>
                                    </ul>
                                </div>
                                <div>
                                    This template contains few basic elements that you have to fill to import into AidStream. Please make sure that you follow the structure and format of the template.
                                    For more details, please follow <a href="https://github.com/younginnovations/aidstream-new/wiki/Activity-Creation#2-bulk-activity-import" target="_blank">here</a>.
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
        var checkSessionRoute = '{{ route('activity.check-session-status')}}';
    </script>
    <script src=" {{ asset('js/csvImporter/checkSessionStatus.js') }}"></script>
@stop
