@extends('app')

@section('title', trans('title.results').' - ' . $activityData->IdentifierTitle)

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>@lang('title.activity_results')
                        @if(count($results) > 0)
                            <div class="panel-action-btn">
                                <a href="{{ route('activity.result.create', $id) }}" class="btn btn-primary add-new-btn">@lang('global.add_new_result')</a>
                                <a href="{{ route('activity.result.upload-csv', $id) }}" class="btn btn-primary add-new-btn">@lang('global.upload_results')</a>
                                <a href="{{ route('activity.show', $id) }}" class="btn btn-primary btn-view-it">@lang('global.view_activity')</a>
                            </div>
                        @else
                            <div class="panel-action-btn">
                                <a href="{{ route('activity.show', $id) }}" class="btn btn-primary btn-view-it">@lang('global.view_activity')</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper result-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @if(count($results) > 0)
                                <table class="table table-striped" id="data-table">
                                    <thead>
                                    <tr>
                                        <th width="10%" class="no-sort">@lang('global.sn')</th>
                                        <th width="45%" class="default-sort">@lang('global.title')</th>
                                        <th width="30%">@lang('global.type')</th>
                                        <th width="15%" class="no-sort">@lang('global.actions')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($results as $resultIndex=>$result)
                                        <tr data-href="{{ route('activity.result.show', [$id, $result->id]) }}" class="clickable-row">
                                            <td>{{ $resultIndex + 1 }}</td>
                                            <td class="activity_title">
                                                {{ $result->title }}
                                            </td>
                                            <td>
                                                {{ $result->type }}
                                            </td>
                                            <td>
                                                <div class="activity_actions">
                                                    <a href="{{ route('activity.result.show', [$id, $result->id]) }}"
                                                       class="view">View</a>
                                                    <a href="{{ route('activity.result.edit', [$id, $result->id]) }}"
                                                       class="edit">@lang('global.edit')</a>
                                                    <a href="{{ url(sprintf('activity/%s/result/%s/delete', $id, $result->id)) }}"
                                                       class="delete">@lang('global.delete')</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center no-data no-result-data">
                                    <p>@lang('global.not_added',['type' => 'Results']).</p>
                                    <a href="{{ route('activity.result.create', $id) }}" class="btn btn-primary">@lang('global.add_new_result')</a>
                                    <a href="{{ route('activity.result.upload-csv', $id) }}" class="btn btn-primary btn-upload">@lang('global.upload_results')</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection
