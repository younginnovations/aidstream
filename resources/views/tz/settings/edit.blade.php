@extends('tz.base.sidebar')

@section('title', 'Settings')

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
                            {{ Form::model($settings, array('route' => array('settings.update', $settings['id']), 'method' => 'PUT')) }}
                                @include('tz.settings.partials.edit-form')
                            {!! Form::submit('Edit Settings', ['class' => 'btn btn-primary btn-create pull-left']) !!}
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
