@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity Contact Info</div>
                    <div class="panel-body">
                        <h3>{{ $activityData->IdentifierTitle }}</h3>
                        {!! form($form) !!}
                        <div class="collection-container hidden"
                             data-prototype="{{ form_row($form->contact_info->prototype()) }}">
                        </div>
                    </div>
                </div>
            </div>
            @include('includes.activity.element_menu')
        </div>
    </div>
@endsection
