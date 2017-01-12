@extends('app')

@section('title', trans('title.downloads'))

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @if($loggedInUser->userOnBoarding)
                    @include('includes.steps')
                @endif
                @include('includes.breadcrumb')
                <div class="panel panel-default">
                    <div class="element-panel-heading">
                        <div>@lang('global.download_data')</div>
                    </div>
                    <div class="panel-body panel-download">
                        <div class="download-wrapper">
                            <div class="download-block">
                                <div class="download-data-title">@lang('global.simple')</div>
                                <p>@lang('global.simple_text')
                                </p>
                                <a href="{{route('download.simple')}}" class="btn btn-primary">@lang('global.download')</a>
                            </div>
                            <div class="download-block">
                                <div class="download-data-title">@lang('global.complete')</div>
                                <p>@lang('global.complete_text')</p>
                                <a href="{{ route('download.complete') }}" class="btn btn-primary">@lang('global.download')</a>
                            </div>
                            <div class="download-block download-transaction-block">
                                <div class="download-data-title">@lang('global.transactions')</div>
                                <p>@lang('global.transactions_text')</p>
                                <a href="{{ route('download.transaction') }}" class="btn btn-primary">@lang('global.download')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
