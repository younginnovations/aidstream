@extends('app')

@section('title', trans('title.upload_transaction').' - ' . $activity->IdentifierTitle)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @if (isset($response))
                    <div class="alert alert-{{$response['type']}}">
                        <span>{!! message($response) !!}</span>
                    </div>
                @endif
                <div id="import-status-placeholder"></div>
                <div class="element-panel-heading">
                    <div>
                        <span>@lang('global.upload_transaction')</span>
                        <div class="element-panel-heading-info">
                            <span>{{$activity->IdentifierTitle}}</span>
                        </div>
                        <div class="panel-action-btn btn-action-wrap">
                            <a href="{{ route('activity.transaction.index', $id) }}"
                               class="btn btn-primary back-to-transaction btn-view-it">@lang('global.back_to_transaction_list')</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="create-form">
                                {!! form($form) !!}
                                <p class="update-transaction">@lang('global.template_updated')</p>
                            </div>
                            <div class="download-transaction-wrap">
                                <a href="{{route('activity.import-transaction.download-simple-csv').'?'.date('Y-m-d H:i:s')}}" class="btn btn-primary btn-form btn-submit">@lang('global.download_simple_transaction_template')</a>
                                <div>@lang('global.simple_transaction_text')
                                </div>
                            </div>
                            <div class="download-transaction-wrap">
                                <a href="{{route('activity.import-transaction.download-detailed-csv').'?'.date('Y-m-d H:i:s')}}" class="btn btn-primary btn-form btn-submit">@lang('global.download_detailed_transaction_template')</a>
                                <div>@lang('global.detailed_transaction_text')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@stop
@section('script')
    @if(session()->has('import-status'))
        <script>
            var activity = "{{$id}}";
            var checkSessionRoute = '{{ route('activity.import-transaction.check-status', $id)}}';
        </script>
        <script src="{{ asset('js/csvImporter/transaction/checkSessionStatus.js') }}"></script>
    @endif
@stop

