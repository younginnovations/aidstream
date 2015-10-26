@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Activities</div>

                    <div class="panel-body">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Organization Name</th>
                                <th>Users</th>
                                <th>Activities</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($organizations as $key=>$organization)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $organization->name}}
                                        <div>@foreach($organization->users as $user)
                                                @if($user['role_id'] == 1)
                                                    {{$user['email']}}
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>{{ count($organization->users) }}</td>
                                    <td>{{ count($organization->activities) }}</td>
                                    <td>{{ $organization->orgStatus}}</td>
                                    <td>
                                        <div class="organization_actions">
                                            <a href="{{ route('admin.edit-organization', $organization->id) }}">Edit</a> |
                                            <a href="{{ route('admin.masquerade-organization', [$organization->id, $organization->users[0]['id']]) }}">Masquerade</a> |
                                            <a href="{{ route('admin.change-organization-status', [$organization->id, ($organization->status == 1) ? 0 : 1]) }}">{{$organization->orgStatus}}</a> |
                                            <a href="{{ route('admin.delete-organization', $organization->id) }}">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Organization Registered Yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            @include('includes.superAdmin.side_bar_menu')
        </div>
    </div>
@endsection

