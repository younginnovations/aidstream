@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading"><a href="{{url('admin/dashboard')}}">Dashboard</a> > Create Organisation Group</div>
                    <div class="panel-body">
                        {!! form_start($form) !!}

                        <div class="panel panel-default">
                            <div class="panel-heading">New Organisation Group</div>
                            <div class="panel-body">
                                {!! form_row($form->new_organization_group) !!}
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Group Admin Information</div>
                            <div class="panel-body">
                                {!! form_row($form->group_admin_information) !!}
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
