@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-content-heading panel-title-heading">Default Aid Type of <span>{{$activityData->IdentifierTitle}}</span></div>
                    <div class="panel-body">
                        {!! form($form) !!}
                    </div>
                </div>
            </div>
            @include('includes.activity.element_menu')
        </div>
    </div>
@endsection
