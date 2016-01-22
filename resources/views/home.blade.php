@extends('app')

@section('title', 'Dashboard')

@section('content')
    @if(Session::get('message'))
        <div class="alert alert-success">
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('trans.home')</div>
                    <div class="panel-body">
                        You are logged in!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
