@extends('app')

@section('title', 'Activity Location - ' . $activityData->IdentifierTitle)

@section('head')
    <link rel="stylesheet" href="{{url('/css/leaflet.css')}}"/>
@endsection

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>Location</span>
                        <div class="element-panel-heading-info"><span>{{$activityData->IdentifierTitle}}</span></div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                            <div class="collection-container hidden"
                                 data-prototype="{{ form_row($form->location->prototype()) }}">
                            </div>
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
        @endsection

        @section('foot')
            <script type="text/javascript" src="{{url('/js/leaflet.js')}}"></script>
            <script type="text/javascript" src="{{url('/js/map.js')}}"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('form .map_container').each(function () {
                        initMap($(this).attr('id'));
                    });

                    $('form').delegate('.add_to_collection', 'click', function () {
                        setTimeout(function () {
                            initMap($('form .map_container').last().attr('id'));
                        }, 500);
                    });
                })
            </script>
@endsection
