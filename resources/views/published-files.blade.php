@extends('app')

@section('title', 'Published Files')

@section('content')

    {{Session::get('message')}}

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper published-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <div class="panel panel-default">
                    <div class="panel-content-heading">Published Files</div>
                    <h2 class="panel-sub-heading">Organizations Published Files</h2>
                    <div class="panel-body">
                        @if(count($list) > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="30px"></th>
                                    <th>Filename</th>
                                    <th>Published Date</th>
                                    <th>Registered in IATI Registry</th>
                                    <th>Preview As</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $file)
                                    <tr>
                                        <td><input type="checkbox"/></td>
                                        <td><a href="{{ url('/uploads/files/organization/' . $file->filename) }}"
                                               target="_blank">{{ $file->filename }}</a></td>
                                        <td>{{ changeTimeZone($file->updated_at ) }}</td>
                                        <td>{{ $file->published_to_register ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <a href="{{ 'http://tools.aidinfolabs.org/csv/direct_from_registry/?xml=' . url('/uploads/files/organization/' . $file->filename) }}"
                                               target="_blank">CSV</a></td>
                                        <td><a href="{{ route('list-published-files', ['delete', $file->id]) }}" class="delete">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center no-data no-document-data">
                                You haven't published any organization file yet.
                            </div>
                        @endif
                    </div>

                    <h2 class="panel-sub-heading">Activities Published Files</h2>
                    <div class="panel-body">
                        @if(count($activity_list) > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="30px"></th>
                                    <th>Filename</th>
                                    <th>Published Date</th>
                                    <th>Registered in IATI Registry</th>
                                    <th>Preview As</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($activity_list as $file)
                                    <tr>
                                        <td><input type="checkbox"/></td>
                                        <td><a href="{{ url('/uploads/files/activity/' . $file->filename) }}"
                                               target="_blank">{{ $file->filename }}</a></td>
                                        <td>{{ changeTimeZone($file->updated_at) }}</td>
                                        <td>{{ $file->published_to_register ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <a href="{{ 'http://tools.aidinfolabs.org/csv/direct_from_registry/?xml=' . url('/uploads/files/organization/' . $file->filename) }}"
                                               target="_blank">CSV</a></td>
                                        <td>
                                            @if($file->published_to_register == 0)
                                                <a href="{{ route('delete-published-file', [$file->id])}}" class="delete"> Delete </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center no-data no-document-data">
                                You haven't published any activity file yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
