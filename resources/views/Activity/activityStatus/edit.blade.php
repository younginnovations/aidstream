@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="panel-content-heading">Activity Status</div>
                <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3>{{$activityData->IdentifierTitle}}</h3>
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @include('includes.activity.element_menu')
        </div>
    </div>
</div>
@endsection

