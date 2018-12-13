@extends('np.base.base')

@section('title', trans('lite/title.transaction'))

@section('content')
    {{Session::get('message')}}

    <div class="col-xs-9 col-lg-9 content-wrapper activity-wrapper">
        @include('includes.response')
        <div id="xml-import-status-placeholder"></div>
        <div class="panel panel-default">
            <div class="panel__heading">
                <div class="panel__title">{{$type}}</div>
            </div>
            <div class="panel__body">
                <div class="create-form user-form">
                    <div class="row">
                        {!! form_start($form) !!}
                        <div class="col-md-9">
                            @foreach($ids as $index => $id)
                                <input name="ids[]" type="hidden" value={{ $id }}>
                            @endforeach
                            {!! form_until($form,'add_more_transaction') !!}
                        </div>
                        <div class="border-btn-line">
                            {!! form_rest($form) !!}
                            <a class='btn btn-form' style="margin-left:0px;padding-top:15px;" href="{{route('np.activity.show', $activityId)}}">@lang('lite/global.cancel')</a>
                        </div>
                        {!! form_end($form) !!}
                        <input class="ids" type="hidden" data-ids=$ids>

                    </div>
                </div>
            </div>
        </div>
        <div class="transaction-container hidden"
             data-prototype="{{ form_row($form->{strtolower($type)}->prototype()) }}"></div>
    </div>
@stop
@section('script')
    <script type="text/javascript" src="{{ url('/lite/js/createActivity.js') }}"></script>
    <script type="text/javascript">
        CreateActivity.editTextArea({!! empty(!$form->getModel()) !!});
        CreateActivity.addToCollection();
    </script>
@endsection
