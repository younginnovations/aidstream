@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 ">
                <h3>Activity : {{$activity->IdentifierTitle}}</h3>
                <h4>Update Transaction</h4>
                {!! form($form) !!}
                <div class="collection-container hidden" data-prototype="{{ form_row($form->transaction->prototype()) }}"></div>
            </div>
            <div class="col-xs-4">
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@stop