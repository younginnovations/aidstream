@extends('app')

@section('title', 'Users')

@section('content')

    {{Session::get('message')}}

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper list-user-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <div class="panel panel-default">
                    <div class="panel-content-heading">User List</div>
                    @if(count($users) > 0)
                    <div class="panel-body">
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
                                      <a href="{{ route('admin.delete-user', $value->id) }}" class="delete">Delete</a>
                                  </td>
                              </tr>
                          @endforeach
                          </tbody>
                      </table>
                    @else
                    <div class="text-center no-data no-user-data">
                      You haven’t added any user yet.
                      <a href="{{ route('admin.register-user') }}" class="btn btn-primary">Add a user</a>
                    </div>
                    @endif
                  </div>
                </div>
            </div>
        </div>
    </div>

@stop
