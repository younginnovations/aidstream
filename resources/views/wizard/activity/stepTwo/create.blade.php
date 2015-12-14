@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                <div class="panel-content-heading">Activity Title and Description</div>
                <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">                    
                        <div class="panel-body">
                            <div class="panel panel-default panel-element-detail panel-add-wizard">
                                <div class="panel-heading">Step 2</div>
                                <div class="panel-body">
                                    <h4>Your iati identifier is : {{ $iatiIdentifier['iati_identifier_text'] }}</h4>
                                    <div class="create-form create-wizard-form">
                                        {!! form($form) !!}
                                    </div>
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
