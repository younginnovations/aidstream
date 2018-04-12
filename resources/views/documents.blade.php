@extends('app')

@section('title', 'Documents')

@section('content')

    {{Session::get('message')}}

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper document-wrapper">
                @include('includes.response')
                @if($loggedInUser->userOnBoarding)
                    @include('includes.steps')
                @endif
                @include('includes.breadcrumb')
                <div class="panel panel-default">
                    <div class="element-panel-heading">
                        <div>@lang('global.documents')</div>
                    </div>
                    <div class="panel-body">
                        @if(count($documents) > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="30px">@lang('global.sn')</th>
                                    <th width="60%">@lang('global.document_link')</th>
                                    <th width="20%">@lang('global.activity_identifiers')</th>
                                    <th>@lang('global.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($documents as $index => $document)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{--*/
                                            $identifiers = (array) $document['activities'];
                                            $identifierList = [];
                                            /*--}}
                                            @if (!$identifiers)
                                                <a href="{{ url('/files/documents') . '/' . rawurlencode($document['filename']) }}">{{ url('/files/documents') . '/' . $document['filename'] }}</a>
                                            @else
                                            {{--*/$url = explode('/', rtrim($document['url'], '/'))/*--}}
                                                <a href="{{ url('/files/documents') . '/' . rawurlencode(end($url)) }}">{{ $document['url'] }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($identifiers as $activityId => $identifier)
                                                {{--*/ $identifierList[] = sprintf('<a href="%s">%s</a>', route('activity.show', [$activityId]), $identifier); /*--}}
                                            @endforeach
                                            {!! implode(', ', $identifierList) !!}
                                        </td>
                                        <td>
                                            @if (!$identifiers)
                                                <a href="{{ route('document.delete', $document['id']) }}"
                                                   class="delete">Delete</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center no-data no-document-data">
                                @lang('global.not_added',['type' =>trans('global.document')])
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
