<table class="table published-table">
    <thead>
    <tr>
        <th width="120px">S. N.</th>
        <th width="60%">Organisation File</th>
        <th align="right" width="250px">Actions</th>
    </tr>
    </thead>
    <div class="pull-right sync">
        <a href="{{ route('superadmin.reSyncOrganizationData', $organization->id) }}" class="sync-link">Sync</a>
    </div>
    <tbody>
    @forelse ($organizationPublishedFiles as $index => $publishedFile)
        <tr>
            <td>
                {{ $index + 1 }}
            </td>
            <td>
                @if (file_exists(public_path('/files/xml/') . '/' . $publishedFile->filename))
                    <a href="{{ url('/files/xml/') . '/' . $publishedFile->filename }}">{{ $publishedFile->filename }}</a>
                @else
                    {{ $publishedFile->filename }}
                @endif
            </td>
            <td>
                @if (!$publishedFile->published_to_register)
                    {!! Form::open(['method' =>'DELETE', 'url' => route('superadmin.deleteOrganizationXmlFile', ['organizationId' => $organization->id, 'fileId' => $publishedFile->id])]) !!}
                    {!! Form::submit('Delete') !!}
                    {!! Form::close() !!}
                @else
                    <a href="{{ route('superadmin.unlinkOrganizationXmlFile', [$organization->id, $publishedFile->id]) }}" class="btn pull-left">Unlink</a>
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
