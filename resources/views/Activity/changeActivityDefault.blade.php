@extends('app')

@section('title', trans('title.activity_defaults'))

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="panel-content-heading panel-title-heading panel-change-activity-heading">
                    <div>@lang('title.activity_default_values')</div>
                </div>
                <div class="panel panel-default panel-create">
                    <div class="panel-body">
                        <div class="create-form change-activity-form">
                            {!! form($form) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

