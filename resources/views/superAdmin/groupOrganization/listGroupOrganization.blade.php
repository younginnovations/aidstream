@extends('app')

@section('title', 'Organization Groups')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Organization Groups</div>
                    <div class="panel-body">
                        @if(count($organizations) > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>S.N.</th>
                                    <th>Group Name</th>
                                    <th>No. of organizations</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($organizations as $key=>$organization)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $organization->group_name}}</td>
                                        <td>{{ count($organization->assigned_organizations) }}</td>
                                        <td>
                                            <div class="organization_actions">
                                                <a href="{{ route('admin.edit-group', $organization->id) }}" class="edit">Edit</a> |
                                                <a href="{{ route('admin.delete-group', $organization->id) }}" class="delete">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center no-data">No Organization has been grouped Yet ::</div>
                        @endif
                        <a href="{{ route('admin.create-organization-group') }}" class="btn btn-primary">Create Organization Group</a>
                    </div>
                </div>
            </div>
            @include('includes.superAdmin.side_bar_menu')
        </div>
    </div>
@endsection

