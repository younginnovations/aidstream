@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.response')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="element-panel-heading">
                    <h3>Published Files Correction For <strong>{{ $organization->name }}</strong></h3>
                    <small>
                        <small>
                            <div>
                                <b>Publishing Type</b>: {{ $settings->publishing_type }}<br/>
                                <b>Registry Info</b>: <br><i>Publisher Id</i>: {{ $settings->registry_info[0]['publisher_id'] }} <br/> <i>Api Id</i>: {{ $settings->registry_info[0]['api_id'] }}<br>
                                <b>Auto Publish</b>: {{ $settings->registry_info[0]['publish_files'] }}<br>
                                <b>Current Version</b>: {{ $settings->version }}

                            </div>
                        </small>
                    </small>
                    <img class="pull-right" src="{{ $organization->logo_url }}" alt="Organization Logo" width="100" height="100">
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>S. N.</th>
                                <th>Activity File</th>
                                <th>Activities Included</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <div class="pull-right">
                            <strong>
                                <a href="{{ route('superadmin.reSync', $organization->id) }}">Sync</a>
                            </strong>
                        </div>
                        <tbody>
                        @forelse ($publishedFiles as $index => $publishedFile)
                            <tr>
                                <td>
                                    {{ $index + 1 }}
                                </td>
                                <td>
                                    @if ($publishedFile->published_activities)
                                        @foreach ($publishedFile->published_activities as $publishedActivity)
                                            <a href="{{ url('/files/xml/') . '/' . $publishedActivity }}">{{ $publishedActivity }}, </a>
                                        @endforeach
                                    @else
                                        None
                                    @endif
                                </td>
                                <td>
                                    @if (file_exists(public_path('/files/xml/') . '/' . $publishedFile->filename))
                                        <a href="{{ url('/files/xml/') . '/' . $publishedFile->filename }}">{{ $publishedFile->filename }}</a>
                                    @else
                                        {{ $publishedFile->filename }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('superadmin.unlinkXmlFile', [$organization->id, $publishedFile->id]) }}">Unlink</a>

                                    @if (!$publishedFile->published_to_register)
                                        {!! Form::open(['method' =>'DELETE', 'url' => route('superadmin.deleteXmlFile', ['organizationId' => $organization->id, 'fileId' => $publishedFile->id])]) !!}
                                        {!! Form::submit('Delete') !!}
                                        {!! Form::close() !!}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    <b>No Files Found.</b>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
