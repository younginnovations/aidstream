@extends('app')

@section('head')
    <link rel="stylesheet" href="{{url('/css/leaflet.css')}}"/>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Location</div>

                    <div class="panel-body">
                        {!! form($form) !!}

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
