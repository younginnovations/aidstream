@extends('app')

@section('content')
    <div class="container main-container admin-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper activity-wrapper">
                <div class="panel panel-default">
                    <div class="panel-content-heading">
                        <div>Settings > ChangeLog</div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped" id="data-table">
                            <thead>
                            <span>Your current files are</span>
                            <tr>
                                <th width="30%">Current File</th>
                                <th class="default-sort">Activities Included</th>
                                <th class="status">Published Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($changes['previous'] as $index => $change)
                                    <tr>
                                        <td>
                                            <a href="{{ url('/files/xml/') . '/' . $index }}">{{ $index }}</a>
                                        </td>
                                        <td>
                                            @forelse($change['included_activities'] as $activityId => $activity)
                                                <span><a href="{{ url('/files/xml/') . '/' . $activity }}">{{ $activity }}</a></span><br>
                                            @empty
                                                <span>No Activities Included.</span><br>
                                            @endforelse
                                        </td>
                                        <td>
                                            {{ $change['published_status'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="panel-body">
                        These files will be generated after your publishing type changes to {{ ucfirst($changes['segmentation']) }}.

                        <table class="table table-striped" id="data-table">
                            <thead>
                            <tr>
                                <th width="30%">New Files</th>
                                <th width="default-sort">Activities Included</th>
                                <th class="status">Published Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($changes['changes'] as $index => $change)
                                <tr>
                                    <td><a href="{{ url('files/xml/') .'/'. $index }}">{{ $index }}</a></td>
                                    <td>
                                        @forelse($change['included_activities'] as $activityId => $activity)
                                            <span><a href="">{{ $activity }}</a></span><br>
                                        @empty
                                            <span>No Activities Included.</span><br>
                                        @endforelse
                                    </td>
                                    <td>
                                        {{ $change['published_status'] }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <form action="{{ route('change-segmentation') }}" method="POST" class="form-group">
                        Do you want to continue ??
                        <input type="hidden" value="{{ csrf_token() }}" name="_token">
                        <input type="hidden" value="{{ $organizationId }}" name="organizationId">
                        <input type="hidden" value="{{ json_encode($changes) }}" name="changes">
                        <input type="hidden" value="{{ json_encode($settings) }}" name="settings">

                        <input type="submit" value="Yes">
                        <a href="{{ route('settings.index') }}" class="btn btn-danger">No</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
