@extends('Activity.activityBaseTemplate')

@section('title', 'Activity Results - ' . $activityData->IdentifierTitle)

@inject('getCode', 'App\Helpers\GetCodeName')

@section('activity-content')
    <div class="element-panel-heading">
        <div>
            <span>@lang('activityView.document_link')</span>
            <div class="element-panel-heading-info">
                <span>{{$activityData->IdentifierTitle}}</span>
            </div>
            <div class="panel-action-btn">
                <a href="{{route('activity.show',$id)}}" class="btn btn-primary">View Activity</a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
        <div class="activity-element-wrapper">
            @foreach($documentLinks as $documentLink)
                <div class="activity-element-list">
                    <div class="activity-element-label">@lang('activityView.title')</div>
                    <div class="activity-element-info">
                        {!! getFirstNarrative($documentLink['document_link']['title'][0]) !!}
                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($documentLink['document_link']['title'][0]['narrative'])])
                    </div>
                </div>
                <div class="activity-element-list">
                    <div class="activity-element-label">@lang('activityView.document_link')</div>
                    <div class="activity-element-info">{!! getClickableLink($documentLink['document_link']['url']) !!}</div>
                </div>
                <div class="activity-element-list">
                    <div class="activity-element-label">@lang('activityView.format')</div>
                    <div class="activity-element-info">{{ $documentLink['document_link']['format'] }}</div>
                </div>
                <div class="activity-element-list">
                    <div class="activity-element-label">@lang('activityView.category')</div>
                    <div class="activity-element-info">
                        @foreach($documentLink['document_link']['category'] as $category)
                            <div>{!! getCodeNameWithCodeValue('DocumentCategory' , $category['code'] , -5) !!} </div>
                        @endforeach
                    </div>
                </div>
                <div class="activity-element-list">
                    <div class="activity-element-label">@lang('activityView.language')</div>
                    <div class="activity-element-info">{!! checkIfEmpty(getDocumentLinkLanguages($documentLink['document_link']['language'])) !!}</div>
                </div>
                @if(session('version') != 'V201')
                    <div class="activity-element-list">
                        <div class="activity-element-label">@lang('activityView.document_date')</div>
                        <div class="activity-element-info">{!! checkIfEmpty(formatDate(getVal($documentLink->toArray(),['document_link','document_date',0,'date']))) !!}</div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
