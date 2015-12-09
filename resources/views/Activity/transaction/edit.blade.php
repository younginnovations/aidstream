@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 ">
                <div class="panel-content-heading panel-title-heading">Editing Transaction of <span>{{$activity->IdentifierTitle}}</span></div>
                {!! form($form) !!}
                <div class="collection-container hidden" data-prototype="{{ form_row($form->transaction->prototype()) }}"></div>
            </div>
            @include('includes.activity.element_menu')
        </div>
    </div>
@stop
