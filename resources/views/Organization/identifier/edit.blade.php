@extends('app')

@section('title', 'Organization Identifier')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="panel-content-heading">
                    <div>Organisation Identifier
                    <div class="panel-action-btn">
                        <a href="{{route('organization.show', $id)}}" class="btn btn-primary">View Organisation Data
                        </a>
                    </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="create-activity-form">
                                {!! form_row($form->reporting_org->getChildren()[0]->reporting_organization_identifier) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @include('includes.menu_org')
            </div>
        </div>
    </div>
@endsection
