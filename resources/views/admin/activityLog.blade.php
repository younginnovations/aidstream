@extends('app')

@section('title', 'Activity Log')

@section('content')
    <div class="container main-container admin-container">
        <div class="row">
            <div class="panel-content-heading">
                <div>Activity Logs</div>
            </div>
            <div class="col-xs-12 col-lg-8 organization-wrapper">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div>
                            <form id="filter_organization">
                                <select name="orgId" id="organization" class="ignore_change form-control">
                                    <option value="">Select Organization</option>
                                    @foreach($superAdmins as $superAdmin)
                                        <option value="sa-{{ $superAdmin->id }}" @if($orgId == "sa-$superAdmin->id") selected="selected" @endif>Super Admin - {{ $superAdmin->name }}</option>
                                    @endforeach
                                    @foreach($organizations as $organization)
                                        <option value="{{ $organization->id }}" @if($orgId == $organization->id) selected="selected" @endif>{{ $organization->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="200px">Date Time</th>
                                <th>Action</th>
                                <th width="200px">User Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($activity as $key => $value)
                                <tr>
                                    <td>{{ $value->created_date }}</td>
                                    <td>{!! trans($value->action, $value->param) !!}</td>
                                    <td>{{$value->user ? $value->user->username : 'The user has been deleted.'}}</td>
                                </tr>
                            @empty
                                <td class="text-center no-data" colspan="4">No Activity Log Yet::</td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('includes.superAdmin.side_bar_menu')
        </div>
    </div>
@stop

@section('foot')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#organization').change(function() {
                window.location = "{{ route('admin.activity-log') }}" + ($(this).val() == "" ? "" : '/organization/' + $(this).val());
            });
        });
    </script>
@endsection
