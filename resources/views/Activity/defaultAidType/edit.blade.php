@extends('app')

@section('title', trans('title.default_aid_type').' - ' . $activityData->IdentifierTitle)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>@lang('element.default_aid_type')</span>
                        <div class="element-panel-heading-info"><span>{{$activityData->IdentifierTitle}}</span></div>
                        <div class="panel-action-btn">
                            <a href="{{ route('activity.show', $id) }}" class="btn btn-primary btn-view-it">@lang('global.view_activity')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @if(session('version') == 'V203')
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                            <div class="collection-container hidden"
                                 data-prototype="{{ form_row($form->default_aid_type->prototype()) }}">
                            </div>
                            @else 
                            <div class="create-activity-form">
                                {!! form($form) !!}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    //remove duplicate aid type values
    $(document).ready(function(){
        var hidden = $.find('.create-form .hidden');
        hidden.map(d => {
            $(d).children('select').val('');
        });
        $(".create-form select").change(function(){
            var hidden = $.find('.create-form .hidden');
            hidden.map(d => {
                $(d).children('select').val('');
            });
        });
    });
</script>
@endsection
