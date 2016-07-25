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
                    <div class="element-panel-heading">
                        <div>User List</div>
                        @if(count($users) > 0)
                            <div>
                                <a href="{{ route('admin.register-user') }}"
                                   class="btn btn-primary add-new-btn pull-right">Add
                                    a user</a>
                            </div>
                        @endif
                    </div>
                    @if(count($users) > 0)
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="40px">S.N.</th>
                                    <th>Name</th>
                                    <th>User Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $key => $value)
                                    <tr class="clickable-row" data-href="{{ route('admin.view-profile', $value->id)}}">
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->first_name}} {{$value->last_name}}</td>
                                        <td>{{$value->username}}</td>
                                        <td>
                                            <a href="{{ route('admin.view-profile', $value->id) }}" class="view"></a>
                                            @if (auth()->user()->isAdmin())
                                                <a href="{{ url(sprintf('organization-user/%s/delete', $value->id)) }}" class="delete">Delete</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @else
                                <div class="text-center no-data no-user-data">
                                    <div>
                                        You haven’t added any user yet.
                                        <a href="{{ route('admin.register-user') }}" class="btn btn-primary">Add a
                                            user</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>

@stop
