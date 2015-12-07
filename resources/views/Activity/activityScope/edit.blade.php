@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity Status</div>
                    <div class="panel-body">
                        <h3>{{$activityData->IdentifierTitle}}</h3>
                        {!! form($form) !!}
                    </div>
                </div>
            </div>
            @include('includes.activity.element_menu')
        </div>
    </div>
@endsection
