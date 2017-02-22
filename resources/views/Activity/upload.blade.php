@extends('app')

@section('title', trans('title.upload_activities'))

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper upload-activity-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        @lang('title.import_bulk_activities')
                    </div>
                    <div>
                        <a href="{{ route('activity.index') }}" class="pull-right back-to-list">
                            <span class="glyphicon glyphicon-triangle-left"></span>@lang('global.back_to_activity_list')
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                            <div class="download-transaction-wrap">
                                <a href="{{route('download.activity-template')}}"
                                   class="btn btn-primary btn-form btn-submit">@lang('global.download_activity_template')</a>
                                <div>
                                    @lang('global.activity_template_text')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop
