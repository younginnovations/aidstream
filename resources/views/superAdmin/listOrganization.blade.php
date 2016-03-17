@extends('app')

@section('title', 'Organizations')

@section('content')
    <div class="container main-container admin-container">
        <div class="row">
            <div class="panel-content-heading">
                <div>Organizations</div>
            </div>
            <div class="col-xs-12 col-lg-8 organization-wrapper">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if(count($organizations) > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="100px">S.N.</th>
                                    <th>Organization Name</th>
                                    <th>Version</th>
                                    <th>Users</th>
                                    <th>Activities</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($organizations as $key=>$organization)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $organization->name}}
                                            <div>@foreach($organization->users as $user)
                                                    @if($user->isAdmin())
                                                        {{$user['email']}}
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>{{ $organization->settings ? $organization->settings->version : '' }}</td>
                                        <td>{{ count($organization->users) }}</td>
                                        <td>{{ count($organization->activities) }}</td>
                                        <td>{{ $organization->orgStatus }}</td>
                                        <td>
                                            <div class="organization_actions">
                                                @if(Auth::user()->isSuperAdmin())
                                                    <a href="{{ route('admin.edit-organization', $organization->id)}}"
                                                       class="edit">Edit</a> |
                                                    <a href="{{ route('admin.change-organization-status', [$organization->id, ($organization->status == 1) ? 0 : 1]) }}">{{($organization->status == 1) ? 'Disable' : 'Enable'}}</a>
                                                    |
                                                    <a href="{{ route('admin.delete-organization', $organization->id) }}"
                                                       class="delete">Delete</a> |
                                                @endif
                                                <a href="{{ route('admin.masquerade-organization', [$organization->id, $organization->users->where('role_id', 1)->first()->id]) }}">Masquerade</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center no-data">No Organization Registered Yet ::</div>
                        @endif
                    </div>
                </div>
            </div>
            @include('includes.superAdmin.side_bar_menu')
        </div>
    </div>
@endsection

