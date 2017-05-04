@extends('app')

@section('title', trans('title.user_logs'))

@section('content')
    <div class="container main-container logs-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper settings-wrapper">
                @include('includes.response')
                @if($loggedInUser->userOnBoarding)
                    @include('includes.steps')
                @endif
                <div class="panel panel-default">
                    <div class="element-panel-heading">
                        <div>
                            @lang('global.activity_log')
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="create-form activity-log-filter-wrap">
                        <form method="POST" id="form-filter" action="{{ route('user-logs.filter') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <div class="collection_form">
                                <label>@lang('global.filter_by')</label>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label>@lang('global.data')</label>
                                    <select class="form-control" name="dataSelection">
                                        <option value="all">@lang('global.all')</option>
                                        <option value="organization"
                                                @if($dataSelection == "organization") selected="selected" @endif>
                                            @lang('global.organisation_data')
                                        </option>

                                        @foreach($activitiesOfOrganization as $activities)
                                            <option value="{{ $activities->id }}"
                                                    @if($dataSelection == $activities->id) selected="selected" @endif >{{ $activities->identifier['activity_identifier'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>@lang('global.user')</label>

                                    <select class="form-control" name="userSelection">
                                        <option value="all">@lang('global.all_users')</option>

                                        @foreach($usersOfOrganization as $users)
                                            <option value="{{ $users->id }}"
                                                    @if($userSelection == $users->id) selected="selected" @endif >{{ $users->first_name }} {{  $users->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button id="btnSearch" name="btnSearch">@lang('global.filter')</button>
                            </div>
                        </form>
                    </div>
                    @if($userLogs->total() > 0)
                    <table class="table table-striped">
                        <thead>
                        <th>@lang('global.date')</th>
                        <th>@lang('global.action')</th>
                        <th>@lang('global.user')</th>
                        </thead>
                        <tbody>
                        @foreach($userLogs as $logs)
                            <tr>
                                <td> {{$logs->created_date}} </td>
                                <td> {!! trans($logs->action, $logs->param) !!}
                                    @if($logs->data)
                                        :
                                        <a href="{{ route('user-logs.viewDeletedData', $logs->user_activity_id) }}"
                                           target="_blank">@lang('global.view_data')</a>
                                    @endif
                                </td>
                                <td> {{$logs->user ? $logs->user->first_name." ".$logs->user->last_name: trans('success.user_deleted')}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $userLogs->render() !!}
                @else
                        <div class="text-center no-data no-activity-data">@lang('global.no_activity_log')::</div>
                    @endif
                    <div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    <script type="text/javascript" src="{{ url('/js/chunk.js') }}"></script>
    <script>
        Chunk.clickPagination("{{ url('user-logs?page=') }}");
        Chunk.submitFilter();
    </script>
@endsection

