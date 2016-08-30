@extends('app')

@section('title', 'Import Status')

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper upload-activity-wrapper">
                @include('includes.response')
                <div id="import-status-placeholder" class="status-nolink"></div>
                <div class="element-panel-heading">
                    <div>
                        Import Activities
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper element-upload-wrapper status-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="status-show-block">
                                <label>Show</label>
                                <select class="tab-select">
                                    <option data-select="all">All</option>
                                    <option data-select="valid">Valid</option>
                                    <option data-select="invalid">Invalid</option>
                                </select>
                            </div>
                            <form action="{{ route('activity.cancel-import') }}" method="POST" id="cancel-import">
                                {{ csrf_field() }}
                                <input type="button" class="btn_confirm hidden" id="cancel-import" data-title="Confirmation" data-message="Are you sure you want to Cancel Activity Import?" value="Cancel">
                            </form>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active checkall-wrap" id="all">
                                    <div id="checkAll" class="hidden">
                                        <label>
                                            <input type="checkbox" id="check-all">
                                            <span></span>
                                        </label>
                                    </div>
                                    <form action="{{ route('activity.import-validated-activities') }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="all-data"></div>
                                        <input type="submit" class="hidden" id="submit-valid-activities" value="Import">
                                    </form>
                                </div>


                                <div role="tabpanel" class="tab-pane checkall-wrap" id="valid">
                                    <div id="checkAll" class="hidden">
                                        <label>
                                            <input type="checkbox" id="check-all">
                                            <span></span>
                                        </label>
                                    </div>
                                    <form action="{{ route('activity.import-validated-activities') }}" method="POST">
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
    <script src="{{ asset('js/csvImporter/accordion.js') }}"></script>
    <script src="{{ asset('js/csvImporter/csvImportStatus.js') }}"></script>
    <script src="{{ asset('js/csvImporter/selectTabs.js') }}"></script>
    <script>
        var checkSessionRoute = '{{ route('activity.check-session-status')}}';
    </script>
    <script src=" {{ asset('js/csvImporter/checkSessionStatus.js') }}"></script>
@stop
