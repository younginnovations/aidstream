@extends('app')

@section('title', 'Organisation Group')

@section('content')
    <div class="container main-container admin-container">
        <div class="row">
            @include('includes.response')
            <div class="panel-content-heading">
                <div><a href="{{url('admin/list-organization')}}">Dashboard</a> > Create Organisation
                    Group
                </div>
            </div>
            <div class="col-xs-12 col-lg-8 organization-wrapper">
                <div class="panel panel-default">
                    <div class="panel-body">
                        {!! form_start($form) !!}

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="create-form">
                                    <div class="inner-form-wrapper">
                                        {!! form_row($form->new_organization_group) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="create-form">
                                    <div class="inner-form-wrapper admin-information-wrapper">
                                        {!! form_row($form->group_admin_information) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! form_end($form) !!}
                    </div>
                </div>
            </div>
            @include('includes.superAdmin.side_bar_menu')
        </div>
    </div>
@endsection
