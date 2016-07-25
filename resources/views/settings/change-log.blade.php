@extends('app')

@section('title', 'Change Log')

@section('content')
    <div class="container main-container admin-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper activity-wrapper">
                <div class="panel panel-default">
                    <div class="panel-content-heading">
                        <div>Settings > ChangeLog</div>
                    </div>
                    <div class="panel-body panel-changelog">
                        <p>When you change your “Publishing Type” option, your previous activities file(s) will be unlinked from IATI registry and deleted from AidStream.</p>
                        <table class="table table-striped table-previous-activities" id="data-table">
                            <thead>
                            <span>Your previous activities file(s) are as follows</span>
                            <tr>
                                <th width="50%">Current File</th>
                                <th class="default-sort">Activities Included</th>
                                {{--<th class="status">Published Status</th>--}}
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
                                            <a href="{{ url('/files/xml/') . '/' . $activity }}">{{ $activity }}</a>
                                        @empty
                                            <span>No Activities Included.</span>
                                        @endforelse
                                    </td>
                                    {{--<td>--}}
                                        {{--{{ $change['published_status'] }}--}}
                                    {{--</td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <span>These files will be generated after your publishing type changes to {{ ucfirst($changes['segmentation']) }}.</span>
                        <table class="table table-striped table-new-activities" id="data-table">
                            <thead>
                            <tr>
                                <th width="50%">New Files</th>
                                <th width="default-sort">Activities Included</th>
                                {{--<th class="status">Published Status</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($changes['changes'] as $index => $change)
                                <tr>
                                    <td><a href="{{ url('files/xml/') .'/'. $index }}">{{ $index }}</a></td>
                                    <td>
                                        @forelse($change['included_activities'] as $activityId => $activity)
                                            <a href="">{{ $activity }}</a>
                                        @empty
                                            <span>No Activities Included.</span>
                                        @endforelse
                                    </td>
                                    {{--<td>--}}
                                        {{--{{ $change['published_status'] }}--}}
                                    {{--</td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if (getVal($settings, ['registry_info', 0, 'publish_files']) == 'yes')
                            <div class="changelog-message">
                                In your settings page, you have selected {{ ucfirst(getVal($settings, ['registry_info', 0, 'publish_files'])) }} to ‘Automatically Update to IATI Registry when
                                publishing files’. AidStream will publish your newly generated activities file(s) to the IATI registry.
                            </div>
                        @else
                            <div class="changelog-message">
                                In your settings page, you have selected {{ ucfirst(getVal($settings, ['registry_info', 0, 'publish_files'])) }} to ‘Automatically Update to IATI Registry when
                                publishing files’. AidStream will not automatically publish your newly generated activities file(s) to the IATI registry. Please go to the
                                <a href="{{ route('list-published-files') }}">Published Files</a> page and publish your Activity file(s).
                            </div>
                        @endif
                        <form action="{{ route('change-segmentation') }}" method="POST" class="form-group">
                            <p>Do you want to continue with the change to "{{ ucfirst($changes['segmentation']) }}" Publishing Type ?</p>
                            <input type="hidden" value="{{ csrf_token() }}" name="_token">
                            <input type="hidden" value="{{ $organizationId }}" name="organizationId">
                            <input type="hidden" value="{{ json_encode($changes) }}" name="changes">
                            <input type="hidden" value="{{ json_encode($settings) }}" name="settings">

                            <input type="submit" value="Yes" class="btn">
                            <a href="{{ route('publishing-settings') }}" class="btn btn-danger">No</a>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
