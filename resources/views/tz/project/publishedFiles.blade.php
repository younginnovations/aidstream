@extends('tz.base.sidebar')

@section('content')
    <div class="col-xs-9 col-lg-9 content-wrapper published-wrapper">
        @include('includes.response')
        @include('tz.partials.info')
        <h2 class="panel-sub-heading">Published Project Files</h2>
        <div class="panel-body">
            @if(count($publishedFiles) > 0)
                <form action="{{route('activity.bulk-publish')}}" method="POST">
                    <div class="publish-btn"><input type="submit" value="Publish Projects To IATI"></div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="30px"></th>
                            <th>Filename</th>
                            <th>Published Date</th>
                            <th width="200px">Registered in IATI Registry</th>
                            <th>Preview As</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($publishedFiles as $file)
                            <tr>
                                <td><input type="checkbox" name="activity_files[]" value="{{$file->organization_id .':'. $file->filename}}"></td>
                                <td><a href="{{ url('/files/xml/' . $file->filename) }}"
                                       target="_blank">{{ $file->filename }}</a></td>
                                <td>{{ changeTimeZone($file->updated_at) }}</td>
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
                    You haven't published any Projects yet.
                </div>
            @endif
        </div>
    </div>
@stop