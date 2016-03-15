@extends('app')

@section('title', 'Recipient Organization Budget')

@section('content')
    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="panel-content-heading">
                    <div>Recipient Organization Budget</div>
                    <div class="pull-right panel-action-btn">
                        <a href="{{route('organization.show', $organizationId)}}" class="btn btn-primary">View Organization Data
                        </a>
                    </div>
                    </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                        <div class="collection-container hidden"
                             data-prototype="{{ form_row($form->recipient_organization_budget->prototype()) }}">
                        </div>
                    </div>
                </div>
            </div>
            @include('includes.menu_org')
            </div>
        </div>
    </div>
@endsection
