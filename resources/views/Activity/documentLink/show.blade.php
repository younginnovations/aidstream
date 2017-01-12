@extends('Activity.activityBaseTemplate')

@section('title', trans('title.document_link').' - ' . $activityData->IdentifierTitle)

@inject('getCode', 'App\Helpers\GetCodeName')

@section('activity-content')
    <div class="element-panel-heading">
        <div>
            <span>@lang('element.document_link')</span>
            <div class="element-panel-heading-info">
                <span>{{$activityData->IdentifierTitle}}</span>
            </div>
            <div class="panel-action-btn">
                <a href="{{route('activity.document-link.show',[$id, $documentLinkId])}}" class="btn btn-primary">@lang('global.view_document_link')</a>            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
        <div class="activity-element-wrapper">
            @foreach($documentLinks as $documentLink)
                <div class="activity-element-list">
                    <div class="activity-element-label">@lang('elementForm.title')</div>
                    <div class="activity-element-info">
                        {!! getFirstNarrative($documentLink['document_link']['title'][0]) !!}
                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($documentLink['document_link']['title'][0]['narrative'])])
                    </div>
                </div>
                <div class="activity-element-list">
                    <div class="activity-element-label">@lang('element.document_link')</div>
                    <div class="activity-element-info">{!! getClickableLink($documentLink['document_link']['url']) !!}</div>
                </div>
                <div class="activity-element-list">
                    <div class="activity-element-label">@lang('elementForm.format')</div>
                    <div class="activity-element-info">{{ $documentLink['document_link']['format'] }}</div>
                </div>
                <div class="activity-element-list">
                    <div class="activity-element-label">@lang('elementForm.category')</div>
                    <div class="activity-element-info">
                        @foreach($documentLink['document_link']['category'] as $category)
                            <div>{!! getCodeNameWithCodeValue('DocumentCategory' , $category['code'] , -5) !!} </div>
                        @endforeach
                    </div>
                </div>
                <div class="activity-element-list">
                    <div class="activity-element-label">@lang('elementForm.language')</div>
                    <div class="activity-element-info">{!! checkIfEmpty(getDocumentLinkLanguages($documentLink['document_link']['language'])) !!}</div>
                </div>
                @if(session('version') != 'V201')
                    <div class="activity-element-list">
                        <div class="activity-element-label">@lang('elementForm.document_date')</div>
                        <div class="activity-element-info">{!! checkIfEmpty(formatDate(getVal($documentLink->toArray(),['document_link','document_date',0,'date']))) !!}</div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
