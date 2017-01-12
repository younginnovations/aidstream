@if(!emptyOrHasEmptyTemplate($documentLinks))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.document_link') @if(array_key_exists('Document Link',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                @foreach($documentLinks as $documentLink)
                    <li>{!! getClickableLink($documentLink['document_link']['url']) !!}</li>
                    <div class="toggle-btn">
                        <span class="show-more-info">@lang('global.show_more_info')</span>
                        <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                    </div>
                    <div class="more-info hidden">
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.title')</div>
                            <div class="activity-element-info">
                                {!! getFirstNarrative($documentLink['document_link']['title'][0]) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($documentLink,['document_link','title',0,'narrative'],[]))])
                            </div>

                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.format')</div>
                            <div class="activity-element-info">{{ $documentLink['document_link']['format'] }}</div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.category')</div>
                            @foreach($documentLink['document_link']['category'] as $category)
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('DocumentCategory' , $category['code'] , -5) !!}</div>
                            @endforeach
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.language')</div>
                            <div class="activity-element-info">{!! checkIfEmpty(getDocumentLinkLanguages($documentLink['document_link']['language'])) !!}</div>
                        </div>
                        @if(session('version') != 'V201')
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.document_date')</div>
                                <div class="activity-element-info">{!! checkIfEmpty(formatDate(getVal($documentLink , ['document_link' , 'document_date' , 0 , 'date']))) !!}</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @if(!request()->route()->hasParameter('document_link'))
            <a href="{{route('activity.document-link.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        @endif
    </div>
@endif
