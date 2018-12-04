@extends('np.base.sidebar')

@section('title', @trans('lite/title.duplicate'))

@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel__heading">
                <div class="panel__title">{{ trans('global.duplicate_activity') }}</div>
            </div>
            <div class="panel__body">
                <div class="create-form user-form">
                    <div class="row">
                        {!! form_start($form) !!}
                        <div class="col-md-9">
                            {!! form_until($form,'activityIdentifier') !!}
                        </div>
                        <div class="border-btn-line no-margin">
                            {!! form_rest($form) !!}
                        </div>
                        {!! form_end($form) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



