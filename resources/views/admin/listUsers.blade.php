@extends('app')
@section('content')

    {{Session::get('message')}}

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                @if(count($users) > 0)
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <td>S.N.</td>
                        <td>Name</td>
                        <td>User Name</td>
                        <td>Action</td>
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
                <a href="{{ route('admin.register-user') }}" class="btn btn-primary">Add User</a>
            </div>
        </div>
    </div>

@stop