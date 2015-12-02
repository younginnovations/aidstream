@extends('app')

@section('content')

    {{Session::get('message')}}

    <div class="container activity-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-heading">Activity Data</div>
                        <div class="panel-body">
                            Activity View
                        </div>
                    </div>
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4 element-sidebar-wrapper">
                    @include('includes.activity.element_menu')
                </div>
        </div>
    </div>
@endsection
