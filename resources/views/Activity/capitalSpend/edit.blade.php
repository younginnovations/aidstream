@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity Capital Spend</div>
                    <div class="panel-body">
                        <h3>{{ $activityData->IdentifierTitle }}</h3>
                        <h2><strong>Capital Spend</strong></h2>
                        {!! form($form) !!}
                    </div>
                </div>
            </div>
            @include('includes.activity.element_menu')
        </div>
    </div>
@endsection
