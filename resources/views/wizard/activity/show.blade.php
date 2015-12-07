@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity Data</div>

                    <div class="panel-body">

                        Activity View

                    </div>
                </div>
            </div>
            @include('wizard.activity.includes.menu_activity_element')
        </div>
    </div>
@endsection
