@extends('app')
@section('content')
    @if(count($errors)>0)
        <div class="alert alert-warning">
            @foreach($errors->all() as $error)
                <ul>
                    <li>{{$error}}</li>
                </ul>
            @endforeach
        </div>
    @endif
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-content-heading panel-title-heading">
                            Upload Activities
                            <a href="{{ route('activity.index') }}" class="btn btn-primary pull-right">Back to Activity List</a>
                        </div>
                        <div class="panel-body">
                            {!! form($form) !!}
                        </div>

                    </div>
                    <a href="/download-activity-template" class="btn btn-primary">Download Activity Template</a>
                    <div>Contains Simplified information about Activity. </div>
            </div>
        </div>
    </div>
@stop
