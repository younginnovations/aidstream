@extends('tz.base.sidebar')

@section('title', 'Settings')
@inject('getCode', 'App\Helpers\GetCodeName')
@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                <div>Settings</div>
            </div>

            <div class="panel-body">
                <div class="create-activity-form">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {!! Form::open(['method' => 'post', 'route' => 'settings.store', 'role' => 'form']) !!}
                            @include('tz.settings.forms')
                            {!! Form::submit('Save Settings', ['class' => 'btn btn-primary btn-create pull-left']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
@stop

@section('script')
    <script src="{{ asset('/js/tz/project.js') }}"></script>
@stop

