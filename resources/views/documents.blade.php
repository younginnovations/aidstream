@extends('app')

@section('title', 'Documents')

@section('content')

    {{Session::get('message')}}

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper document-wrapper">
                @include('includes.breadcrumb')
                <div class="panel panel-default">
                    <div class="panel-content-heading">Documents</div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="30px">S.N.</th>
                                <th>Document Link</th>
                                <th>Activity Identifiers</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($documents as $index => $document)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><a href="{{ $document['url'] }}">{{ $document['url'] }}</a></td>
                                    <td>
                                        {{--*/
                                        $identifiers = (array) $document['activities'];
                                        $identifierList = [];
                                        /*--}}
                                        @foreach($identifiers as $activityId => $identifier)
                                            {{--*/ $identifierList[] = sprintf('<a href="%s">%s</a>', route('activity.show', [$activityId]), $identifier); /*--}}
                                        @endforeach
                                        {!! implode(', ', $identifierList) !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center no-data">No documents found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
