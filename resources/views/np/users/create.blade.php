@extends('np.base.sidebar')

@section('title', trans('lite/title.add_user'))

@section('content')
    {{Session::get('message')}}
    <div class="col-xs-9 col-lg-9 content-wrapper activity-wrapper">
        @include('includes.response')
        <div class="panel panel-default">
            <div class="panel__heading">
                <div class="panel__title">@lang('lite/title.add_user')</div>
            </div>
            <div class="panel__body">
                <div class="create-form user-form">
                    <span class="hidden" id="user-identifier" data-id="{{ $organizationIdentifier }}"></span>
                    <div class="row">
                        {!! form_start($form) !!}
                        <div class="col-md-9">
                            {!! form_until($form,'role_id') !!}
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
@stop
@section('script')
    <script src="{{url('js/chunk.js')}}"></script>
    <script>
        Chunk.usernameGenerator();
    </script>
@stop
