@extends('app')

@section('title', 'Organisations')

@section('content')
    <style>
        .less-description {
            text-overflow: ellipsis;
            overflow: hidden;
            max-width: 100px;
            white-space: nowrap;
        }

        .filterWrapper {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .system-version {
            margin-right: 12px;
        }
    </style>
    <div class="container main-container admin-container">
        <div class="row">
            <div class="panel-content-heading">
                <div>Organisations</div>
            </div>
            <div class="col-xs-12 col-lg-8 organization-wrapper">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="{{ route('admin.list-organization') }}" class="filterWrapper" method="GET">
                            <div id="sysVerSelect" class="system-version">
                                <select name="sysVersion">
                                    <option value="">All System version</option>
                                    @foreach($sysVersions as $sysversion)
                                    <option value="{{ $sysversion->id  }}" {{ $sysversion->id == $selectedSysVersion ? 'selected' : ''}}>{{ $sysversion->system_version  }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div id="verSelect">
                            <select name="version">
                                <option value="">All version
                                </option>
                                @foreach($versions as $version)
                                <option value="{{ $version->version  }}" {{ $version->version == $selectedVersion ? 'selected' : ''}}>{{ $version->version  }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(count($organizations) > 0)
                            <div class="col-md-4 pull-right search-org">
                                    <input type="text" name="organization" placeholder="{{ isset($organizationName) ? $organizationName : 'Search organizations or email' }}" value="{{ isset($organizationName) ? $organizationName : '' }}">
                                    <input type="submit" value="Search">
                                </form>
                            </div>
                            @if (request()->has('organization'))
                                <a href="{{ route('admin.list-organization') }}" class="pull-left back-to-admin-dashboard">Back To Organisations List</a>
                            @endif
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="100px">S.N.</th>
                                    <th width="30%">Organisation Name</th>
                                    <th>Version</th>
                                    <th>System Version</th>
                                    <th>Users</th>
                                    <th>Activities</th>
                                    <th width="180px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($organizations as $key=>$organization)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="less-description">{{ $organization->name}}
                                            <div>{{getVal($organization->users->toArray(), [0, 'email'])}}</div>
                                        </td>
                                        <td>{{ $organization->settings ? $organization->settings->version : '' }}</td>
                                        <td> @if($organization->system_version_id == 1)
                                                    Core
                                                @elseif($organization->system_version_id == 2)
                                                    Lite
                                                @elseif($organization->system_version_id == 3)
                                                    Tz
                                                @elseif($organization->system_version_id == 4)
                                                    Np 
                                                @endif
                                        </td>
                                        <td>{{ count($organization->users) }}</td>
                                        <td>{{ count($organization->activities) }}</td>
                                        <td>
                                            <div class="organization_actions">
                                                @if(session('role_id') == 3)
                                                    <a href="{{ route('admin.hide-organization', [$organization->id,($organization->display) ? 0 : 1 ]) }}"
                                                       title="{{($organization->display) ? 'Hide' : 'Show'}}"
                                                       class="display {{($organization->display) ? 'Yes' : 'No'}}">{{($organization->display) ? 'Yes' : 'No'}}</a>
                                                    <a href="{{ route('admin.edit-organization', $organization->id)}}"
                                                       class="edit" title="Edit">@lang('global.edit')</a>
                                                    <a href="{{ route('admin.change-organization-status', [$organization->id, ($organization->status) ? 0 : 1]) }}"
                                                       class="check-status {{($organization->status) ? 'Disable' : 'Enable'}}"
                                                       title="{{($organization->status) ? 'Disable' : 'Enable'}}">{{($organization->status == 1) ? 'Disable' : 'Enable'}}</a>
                                                    <a href="{{ route('admin.delete-organization', $organization->id) }}" class="delete" title="delete">Delete</a>
                                                @endif
                                                @if (getVal($organization->users->toArray(), [0], false))
                                                    <a href="{{ route('admin.masquerade-organization',
                                                    [$organization->id, getVal($organization->users->toArray(), [0], false) ?
                                                    getVal($organization->users->toArray(), [0, 'id']) :
                                                    $organization->users()->first()->id]) }}"
                                                       class="masquerade" title="Masquerade">Masquerade</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            @if (request()->has('organization'))
                                <div class="col-md-4 pull-right search-org">
                                    <form action="{{ route('admin.list-organization') }}" method="GET">
                                        <input type="text" name="organization" placeholder="Search Organizations" value="{{ isset($organizationName) ? $organizationName : '' }}">
                                        <input type="submit" value="Search">
                                    </form>
                                </div>
                                @if (request()->has('organization'))
                                    <a href="{{ route('admin.list-organization') }}" class="pull-left back-to-admin-dashboard">Back To Organisations List</a>
                                @endif
                                <div class="text-center no-data">No Results Found.</div>
                            @else
                                <div class="text-center no-data">No Organisation Registered Yet ::</div>
                            @endif
                        @endif
                    </div>

                    <div class="wrapper-datatable">
                    <div class="text-center">
                        {!! $organizations->render() !!}
                    </div>

                    <div class="text-center" id="TotalResult">
                            Total results: {{ $organizations->total() }}
                    </div>
                </div>

                </div>
            </div>
            @include('includes.superAdmin.side_bar_menu')
        </div>
    </div>



@endsection

@section('script')
    <script type="text/javascript">
        var masqueradeBtn = document.querySelectorAll('.masquerade');

        var preventClick = false;
        for (var i = 0; i < masqueradeBtn.length; i++) {
            var button = masqueradeBtn[i];
            button.onclick = function (event) {
                if (preventClick) {
                    event.preventDefault();
                }
                preventClick = true;
            }
        }
    </script>
@stop
