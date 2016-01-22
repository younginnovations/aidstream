@extends('app')

@section('title', 'Add Organization')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading"><a href="{{url('admin/dashboard')}}">Dashboard</a> > Add Organization</div>
                    <div class="panel-body">
                        {!! form_start($form) !!}

                        <div class="panel panel-default">
                            <div class="panel-heading">Organization Information</div>
                            <div class="panel-body">
                                {!! form_row($form->organization_information) !!}
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Admin Information</div>
                            <div class="panel-body">
                                {!! form_row($form->admin_information) !!}
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Default Field Values</div>
                            <div class="panel-body">
                                {!! form_row($form->default_field_values) !!}
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Default Field Groups</div>
                            <div class="panel-body">
                                <label><input type="checkbox" class="hidden checkAll"/><span class="btn btn-primary">Check All</span></label>
                                {!! form_row($form->default_field_groups) !!}
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

