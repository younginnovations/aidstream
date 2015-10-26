@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('trans.home')</div>
                    <div class="panel-body">
                        You are logged in!
                    </div>
                </div>
            </div>
            @include('includes.superAdmin.side_bar_menu')
        </div>
    </div>
@endsection
