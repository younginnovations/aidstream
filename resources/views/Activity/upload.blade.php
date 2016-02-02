@extends('app')

@section('title', 'Upload Activities')

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
                    @include('includes.response')
                     <div class="panel-content-heading panel-title-heading">
                            Upload Activities
                            <a href="{{ route('activity.index') }}" class="pull-right back-to-list"><span class="glyphicon glyphicon-triangle-left"></span>Back to Activity List</a>
                        </div>
                     <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper element-upload-wrapper">
                        <div class="panel panel-default panel-upload">
                            <div class="panel-body">
                                <div class="create-form">
                                    {!! form($form) !!}
                                </div>
                                <div class="download-transaction-wrap">
                                    <a href="/download-activity-template" class="btn btn-primary btn-form btn-submit">Download Activity Template</a>
                                    <div>Contains Simplified information about Activity.</div>
                                </div>
                         </div>
                    </div>
              </div>
        </div>
    </div>
@stop
