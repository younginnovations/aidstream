@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                 <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">
                <div class="panel panel-default">
                    <div class="panel-heading">Iati Identifier</div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">Step 1</div>
                            <div class="panel-body">
                                {!! form($form) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('wizard.activity.includes.menu_activity_element')
            </div>
        </div>
    </div>
@endsection
