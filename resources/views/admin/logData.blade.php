@extends('app')

@section('title', 'Activity Log')

@section('head')
    <link href="{{ asset('/css/jquery.jsonview.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container main-container admin-container">
        <div class="row">
            <div class="panel-content-heading">
                <div>Deleted Data</div>
                <div class="panel-action-btn">
                    <button type="button" class="btn btn-primary" id="toggle-btn">Toggle View</button>
                </div>
            </div>
            <div class="col-xs-12 col-lg-8 organization-wrapper">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="json-view" class="create-form"></div>
                    </div>
                </div>
            </div>
            @include('includes.superAdmin.side_bar_menu')
        </div>
    </div>
@stop

@section('foot')
    <script type="text/javascript" src="{{url('/js/jquery.jsonview.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/chunk.js')}}"></script>
    <script type="text/javascript">
        Chunk.toggleData({!! json_encode($data) !!})
    </script>
@endsection
