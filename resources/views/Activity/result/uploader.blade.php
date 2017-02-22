@extends('app')

@section('title', trans('title.upload_results'))

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
                        @lang('title.import_results')
                    </div>
                    <div>
                        <a href="{{ route('activity.result.index', $activityId) }}" class="pull-right btn btn-primary btn-view-it">@lang('global.back_to_result_list')
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper element-import-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                            <div class="download-transaction-wrap">
                                <a href="{{route('activity.result.download-template', $activityId)}}" class="btn btn-primary">
                                    @lang('global.download_result_template')
                                </a>
                                <div>
                                    @lang('global.result_template_text')<a href="https://github.com/younginnovations/aidstream/wiki/How-to-use-the-mass-import-option-for-uploading-results" target="_blank">@lang('global.here')</a>.
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
    @if(session()->has('import-status'))
        <script>
            var checkSessionRoute = '{{ route('activity.result.check-session-status')}}';
            var activity = "{!! $activityId !!}";
        </script>
        <script src="{{ asset('js/csvImporter/result/checkSessionStatus.js') }}"></script>
    @endif
@stop
