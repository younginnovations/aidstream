@extends('app')

@section('title', trans('title.import_status'))

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper upload-activity-wrapper">
                @include('includes.response')
                <div id="import-status-placeholder" class="status-nolink"></div>
                <a class="hidden" id="go_back" href="{{ route('activity.result.upload-csv', $activityId)}}">Go Back</a>
                <div class="element-panel-heading">
                    <div>
                        @lang('title.import_results')
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper element-upload-wrapper status-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="status-show-block">
                                <div id="checkAll" class="hidden">
                                    <label>
                                        <input type="checkbox" class="check-btn">
                                        <span></span>
                                    </label>
                                </div>
                                <label>@lang('global.show')</label>
                                <select class="tab-select">
                                    <option data-select="all">@lang('global.all')</option>
                                    <option data-select="valid">@lang('global.valid')</option>
                                    <option data-select="invalid">@lang('global.invalid')</option>
                                </select>
                            </div>
                            <form action="{{ route('activity.result.cancel-import', $activityId) }}" method="POST" id="cancel-import">
                                {{ csrf_field() }}
                                <input type="button" class="btn_confirm hidden" id="cancel-import" data-title="Confirmation" data-message="{{ trans('global.cancel_csv_import', ['type' => trans('global.result')]) }}" value="Cancel">
                            </form>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="all">
                                    <form action="{{ route('activity.result.import-validated-results', $activityId) }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="all-data"></div>
                                        <input type="submit" class="hidden" id="submit-valid-activities" value="Import">
                                    </form>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="valid">
                                    <form action="{{ route('activity.result.import-validated-results', $activityId) }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="valid-data"></div>
                                        <input type="submit" class="hidden" id="submit-valid-activities" value="Import">
                                    </form>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="invalid">
                                    <div class="invalid-data"></div>
                                </div>
                            </div>
                        </div>

                        <div class="download-transaction-wrap">
                            <div>
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
        var activity = "{!! $activityId !!}";
    </script>
    <script src="{{ asset('js/csvImporter/accordion.js') }}"></script>
    <script src="{{ asset('js/csvImporter/result/csvImportStatus.js') }}"></script>
    <script src="{{ asset('js/csvImporter/selectTabs.js') }}"></script>
    <script>
        var checkSessionRoute = '{{ route('activity.result.check-session-status')}}';
    </script>
    <script src="{{ asset('js/csvImporter/result/checkSessionStatus.js') }}"></script>
@stop
