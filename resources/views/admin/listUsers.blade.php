@extends('app')
@section('content')
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
                            <td><a class="btn btn-small btn-primary" href="{{ route('admin.view-profile', $value->id) }}">view</a>
                                {!! Form::open(array('url' => 'admin/'. $value->id, 'class' => 'pull-right')) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::submit('Delete', array('class' => 'btn btn-warning')) !!}
                                {!! Form::close() !!}
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