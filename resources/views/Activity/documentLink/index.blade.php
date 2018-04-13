@extends('app')

@section('title', trans('title.document_link').' - ' . $activityData->IdentifierTitle)

@inject('getCode', 'App\Helpers\GetCodeName')
@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>@lang('title.document_links')
                        <div class="panel-action-btn">
                            @if(count($documentLinks) > 0)
                                <a href="{{ route('activity.document-link.create', $id) }}" class="btn btn-primary add-new-btn">@lang('global.add_new_document_link')</a>
                            @endif
                            <a href="{{route('activity.show',$id)}}" class="btn btn-primary btn-view-it">@lang('global.view_activity')</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper result-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @if(count($documentLinks) > 0)
                                <table class="table table-striped" id="data-table">
                                    <thead>
                                    <tr>
                                        <th width="30%" class="default-sort">@lang('global.title')</th>
                                        <th width="30%">@lang('global.format')</th>
                                        <th width="25%">@lang('global.category')</th>
                                        <th width="15%" class="no-sort">@lang('global.actions')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($documentLinks as $documentLinkIndex => $documentLink)
                                        <tr data-href="{{ route('activity.document-link.show', [$id, $documentLink->id]) }}" class="clickable-row">
                                            {{--*/
                                                $documentLinkData = $documentLink->document_link;
                                            /*--}}
                                            <td style="word-break: break-all;">
                                                @if(!$documentLinkData['title'][0]['narrative'])
                                                    {{--*/
                                                    $documentLinkData['title'][0]['narrative'] = [['narrative' => '', 'language' => '']];
                                                    /*--}}
                                                @endif
                                                {{--*/
                                                    $url = explode('/', rtrim($documentLinkData['url'], '/'));
                                                    $title = $documentLinkData['title'][0]['narrative'][0]['narrative'];
                                                    $title ?: $title = $url;
                                                /*--}}
                                                <a href="{{ url('/files/documents') . '/' .rawurlencode(end($url)) }}" target="_blank">{{ $title }}</a>
                                            </td>
                                            <td>
                                                {{ $documentLinkData['format'] }}
                                            </td>
                                            <td>
                                                {{ $getCode->getActivityCodeName('DocumentCategory', getVal($documentLinkData, ['category', 0, 'code'])) }}
                                            </td>
                                            <td>
                                                <div class="activity_actions">
                                                    <a href="{{ route('activity.document-link.show', [$id, $documentLink->id]) }}"
                                                       class="view">@lang('global.view')</a>
                                                    <a href="{{ route('activity.document-link.edit', [$id, $documentLink->id]) }}"
                                                       class="edit">@lang('global.edit')</a>
                                                    <a href="{{ url(sprintf('activity/%s/document-link/%s/delete', $id, $documentLink->id)) }}"
                                                       class="delete">@lang('global.delete')</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center no-data no-result-data">
                                    <p>@lang('global.not_added',['type' => 'document']).</p>
                                    <a href="{{ route('activity.document-link.create', $id) }}" class="btn btn-primary">@lang('global.add_new_document_link')</a>
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
