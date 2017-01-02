@extends('app')

@section('title', trans('setting.change_log'))

@section('content')
    <div class="container main-container admin-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper activity-wrapper">
                <div class="panel panel-default">
                    <div class="panel-content-heading">
                        <div>@lang('settings_changelog')</div>
                    </div>
                    <div class="panel-body panel-changelog">
                        <p>@lang('setting.when_you_change_publishing_type')</p>
                        <table class="table table-striped table-previous-activities" id="data-table">
                            <thead>
                            <span>@lang('setting.your_previous_activities')</span>
                            <tr>
                                <th width="50%">@lang('setting.current_file')</th>
                                <th class="default-sort"></th>
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
                                            <span>@lang('setting.no_activities_included')</span>
                                        @endforelse
                                    </td>
                                    {{--<td>--}}
                                    {{--{{ $change['published_status'] }}--}}
                                    {{--</td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <span>@lang('setting.these_files_will_be_generated') {{ ucfirst($changes['segmentation']) }}.</span>
                        <table class="table table-striped table-new-activities" id="data-table">
                            <thead>
                            <tr>
                                <th width="50%">@lang('setting.new_files')</th>
                                <th width="default-sort">@lang('setting.activities_included')</th>
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
                                            <span>@lang('setting.no_activities_included')</span>
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
                                @lang('setting.in_your_settings_page') {{ ucfirst(getVal($settings, ['registry_info', 0, 'publish_files'])) }} @lang('setting.to_automatically_update_iati_registry')
                            </div>
                        @else
                            <div class="changelog-message">
                                @lang('setting.in_your_settings_page') {{ ucfirst(getVal($settings, ['registry_info', 0, 'publish_files'])) }} @lang('setting.to_automatically_update_iati_registry')
                                @lang('setting.please_go_to_the',['route' => route('list-published-files')])
                            </div>
                        @endif
                        <form action="{{ route('change-segmentation') }}" method="POST" class="form-group">
                            <p>@lang('setting.do_you_want_to_continue',['segmentation' => ucfirst($changes['segmentation'])])</p>
                            <input type="hidden" value="{{ csrf_token() }}" name="_token">
                            <input type="hidden" value="{{ $organizationId }}" name="organizationId">
                            <input type="hidden" value="{{ json_encode($changes) }}" name="changes">
                            <input type="hidden" value="{{ json_encode($settings) }}" name="settings">

                            <input type="submit" value="{{ trans('global.yes') }}" class="btn">
                            <a href="{{ route('publishing-settings') }}" class="btn btn-danger">@lang('global.no')</a>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
