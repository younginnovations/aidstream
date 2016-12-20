@extends('app')

@section('title', trans('title.deleted_log_data'))

@section('head')
    <link href="{{ asset('/css/jquery.jsonview.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper settings-wrapper">
                @include('includes.response')
                <div class="panel panel-default">
                    <div class="element-panel-heading">
                        <div>
                            @lang('title.deleted_log_data')
                        </div>
                        <div class="panel-action-btn">
                            <button type="button" class="btn btn-primary" id="toggle-btn">@lang('global.toggleView')</button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div id="json-view" class="create-form"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('foot')
    <script type="text/javascript" src="{{url('/js/jquery.jsonview.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/chunk.js')}}"></script>
    <script type="text/javascript">
        Chunk.toggleData({!! json_encode($data) !!});
    </script>
@endsection
