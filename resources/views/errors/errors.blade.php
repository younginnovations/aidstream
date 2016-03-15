@extends('app')

@section('title', 'Non Accessible Content')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('errors.partials.404')
            </div>
        </div>
    </div>
@endsection
