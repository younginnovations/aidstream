@extends('app')

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                 <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default panel-element-detail">
                        <div class="panel-heading">Reporting Organization</div>
                        <div class="panel-body">
                            {!! form($form) !!}
                            <div class="collection-container hidden"
                                 data-prototype="{{ form_row($form->reporting_org->prototype()) }}">
                            </div>
                        </div>
                        </div>
                    </div>
                @include('includes.menu_org')
            </div>
        </div>
    </div>
@endsection
