@extends('app')

@section('title', 'Published Files')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper published-wrapper">
                @include('includes.response')
                @if($loggedInUser->userOnBoarding)
                    @include('includes.steps')
                @endif
                @if(Session::has('value'))
                    @foreach (Session::get('value') as $key=>$value)
                        @if($key == "unpublished")
                            <div class="alert alert-warning">
                                @else
                                    <div class="alert alert-success">
                                        @endif
                                        <ul>
                                            <li>{{ $value }}</li>
                                        </ul>
                                    </div>
                                    @endforeach
                                @endif

                                @include('includes.breadcrumb')
                                <div class="panel panel-default">
                                    <div class="element-panel-heading">
                                        <div>@lang('global.published_files')</div>
                                    </div>
                                    <h2 class="panel-sub-heading">@lang('global.organisations_published_files')</h2>
                                    <div class="panel-body">
                                        @if(count($list) > 0)
                                            <form action="{{route('org.bulk-publish')}}" method="POST">
                                                <div class="publish-btn"><input type="submit" value="{{trans('global.publish_organisation_to_iati')}}"></div>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th width="30px"></th>
                                                        <th>@lang('global.filename')</th>
                                                        <th>@lang('global.published_date')</th>
                                                        <th width="200px">@lang('global.registered_in_iati_registry')</th>
                                                        <th>@lang('global.preview_as')</th>
                                                        <th>@lang('global.action')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($list as $file)
                                                        <tr>
                                                            <td><input type="checkbox" name="org_files[]" value="{{$file->organization_id .':'. $file->filename}}"/></td>
                                                            <td><a href="{{ url('/files/xml/' . $file->filename) }}"
                                                                   target="_blank">{{ $file->filename }}</a></td>
                                                            <td>{{ lastPublishedDate($file) }}</td>
                                                            <td>{{ $file->published_to_register ? 'Yes' : 'No' }}</td>
                                                            <td>
                                                                <a href="{{ 'http://tools.aidinfolabs.org/csv/direct_from_registry/?xml=' . url('/files/xml/' . $file->filename) }}"
                                                                   target="_blank">CSV</a></td>
                                                            <td>
                                                                @if($file->published_to_register == 0)
                                                                    <a href="{{ route('list-published-files', ['delete', $file->id]) }}"
                                                                       class="delete">Delete</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </form>
                                        @else
                                            <div class="text-center no-data no-document-data">
                                                @lang('global.not_added',['type' => trans('global.organisation_file')])
                                            </div>
                                        @endif
                                    </div>

                                    <h2 class="panel-sub-heading">@lang('global.activities_published_files')</h2>
                                    <div class="panel-body">
                                        @if(count($activity_list) > 0)
                                            <form action="{{route('activity.bulk-publish')}}" method="POST">
                                                <div class="publish-btn"><input type="submit" value="{{trans('global.publish_activities_to_iati')}}"></div>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th width="30px"></th>
                                                        <th>@lang('global.filename')</th>
                                                        <th>@lang('global.published_date')</th>
                                                        <th width="200px">@lang('global.registered_in_iati_registry')</th>
                                                        <th>@lang('global.preview_as')</th>
                                                        <th>@lang('global.action')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($activity_list as $file)
                                                        <tr>
                                                            <td><input type="checkbox" name="activity_files[]" value="{{$file->organization_id .':'. $file->filename}}"></td>
                                                            <td><a href="{{ url('/files/xml/' . $file->filename) }}"
                                                                   target="_blank">{{ $file->filename }}</a></td>
                                                            <td>{{ lastPublishedDate($file) }}</td>
                                                            <td>{{ $file->published_to_register ? 'Yes' : 'No' }}</td>
                                                            <td>
                                                                <a href="{{ 'http://tools.aidinfolabs.org/csv/direct_from_registry/?xml=' . url('/files/xml/' . $file->filename) }}"
                                                                   target="_blank">CSV</a></td>
                                                            <td>
                                                                @if($file->published_to_register == 0)
                                                                    <a href="{{ route('delete-published-file', [$file->id])}}"
                                                                       class="delete"> Delete </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </form>
                                        @else
                                            <div class="text-center no-data no-document-data">
                                                @lang('global.not_added',['type' => trans('global.activity_file')])
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
            </div>
        </div>
@endsection
