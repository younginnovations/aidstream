@extends('app')

@section('content')

    {{Session::get('message')}}

    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">List Published Files</div>

                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Filename</th>
                                <th>Published Date</th>
                                <th>Registered in IATI Registry</th>
                                <th>Preview As</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $file)
                                <tr>
                                    <td><input type="checkbox"/></td>
                                    <td><a href="{{ url('/uploads/files/organization/' . $file->filename) }}"
                                           target="_blank">{{ $file->filename }}</a></td>
                                    <td>{{ $file->updated_at }}</td>
                                    <td>{{ $file->published_to_register ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <a href="{{ 'http://tools.aidinfolabs.org/csv/direct_from_registry/?xml=' . url('/uploads/files/organization/' . $file->filename) }}"
                                           target="_blank">CSV</a></td>
                                    <td><a href="{{ route('list-published-files', ['delete', $file->id]) }}">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No published files found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @include('includes.side_bar_menu')

        </div>
    </div>
@endsection
