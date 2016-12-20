@extends('app')

@section('title', 'Add Organisation')

@section('content')
    <div class="container main-container admin-container">
        <div class="row">
            <div class="panel-content-heading">
                <div>
                    <a href="{{url('admin/list-organization')}}">Dashboard</a> > Add Organisation
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
                                        {!! form_row($form->organization_information) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="create-form">
                                    <div class="inner-form-wrapper admin-information-wrapper">
                                        {!! form_row($form->admin_information) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="create-form">
                                    <div class="inner-form-wrapper">
                                        {!! form_row($form->default_field_values) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="create-form">
                                    <div class="inner-form-wrapper checkall-wrapper">
                                        <h2>Choose elements to show/ hide</h2>
                                        <div class="form-group">
                                            <label><input type="checkbox" class="checkAll"/><span
                                                        class="check-text">@lang('global.check_all')</span></label>
                                        </div>
                                        {!! form_row($form->default_field_groups) !!}
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

