@extends('app')

@section('title', 'Non Accessible Content')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('trans.home')</div>
                    <div class="panel-body">
                        {{ $message }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
