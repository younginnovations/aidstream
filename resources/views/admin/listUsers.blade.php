@extends('app')
@section('content')

    {{Session::get('message')}}

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                <div class="panel panel-default">
                    <div class="panel-content-heading">User List</div> 
                    @if(count($users) > 0)
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>Name</th>
                            <th>User Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $key => $value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->first_name}} {{$value->last_name}}</td>
                                <td>{{$value->username}}</td>
                                <td>
                                    <a href="{{ route('admin.view-profile', $value->id) }}" class="view">View</a>
                                    <a href="{{ url(sprintf('user/%s/delete', $value->id)) }}" class="delete">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="text-center no-data">No users yet :: </div>
                    @endif
                    <a href="{{ route('admin.register-user') }}" class="add">Add User</a>
                </div>
            </div>
        </div>
    </div>

@stop