@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <a href="{{ route('activity.transaction.index', $id) }}" class="btn btn-primary">Back to Transaction List</a>
                <div class="panel panel-default">
                    <div class="panel-heading">Activity Transaction</div>
                    <div class="panel-body">
                        <h3>{{ $activity->IdentifierTitle }}</h3>
                        {!! form($form) !!}
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@stop
