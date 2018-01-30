@if(!emptyOrHasEmptyTemplate($document_link))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.document_link')</div>
            <div class="activity-element-info">
                @foreach($document_link as $documentLink)
                    <li>{!! getClickableLink($documentLink['url']) !!}</li>
                    <div class="toggle-btn">
                        <span class="show-more-info">@lang('global.show_more_info')</span>
                        <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                    </div>
                    <div class="more-info hidden">
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.title')</div>
                            <div class="activity-element-info">
                                {!! getFirstNarrative($documentLink) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($documentLink['narrative'])])
                            </div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.category')</div>
                            <div class="activity-element-info">
                                @foreach($documentLink['category'] as $category)
                                    <li>{!! getCodeNameWithCodeValue('DocumentCategory' , $category['code'] , -5) !!}</li>
                                @endforeach
                            </div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.language')</div>
                            <div class="activity-element-info">{!! checkIfEmpty(getDocumentLinkLanguages($documentLink['language'])) !!}</div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.document_date')</div>
                            <div class="activity-element-info">{!! checkIfEmpty(formatDate(getVal($documentLink , ['document_date' , 0 , 'date']))) !!}</div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.recipient_country')</div>
                            @foreach($documentLink['recipient_country'] as $country)
                                <div class="activity-element-info">
                                    <li>{!! getCountryNameWithCode($country['code']) !!}</li>
                                    <div class="expanded">
                                        <div class="element-info">
                                            <div class="activity-element-label">
                                                @lang('elementForm.narrative')</div>
                                            <div class="activity-element-info">{!! checkIfEmpty(getFirstNarrative($country)) !!}
                                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($country['narrative'])])</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <a href="{{ url('/organization/' . $id . '/document-link') }}" class="edit-element">@lang('global.edit')</a>
        <a href="{{ route('organization.delete-element',[$id,'document_link'])}}" class="delete pull-right">@lang('global.delete')</a>
    </div>
@endif
