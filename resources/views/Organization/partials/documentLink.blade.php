@if(!emptyOrHasEmptyTemplate($document_link))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.document_link')</div>
            <div class="activity-element-info">
                @foreach($document_link as $documentLink)
                    <li>{!! getClickableLink($documentLink['url']) !!}</li>
                    <div class="toggle-btn">
                        <span class="show-more-info">Show more info</span>
                        <span class="hide-more-info hidden">Hide more info</span>
                    </div>
                    <div class="more-info hidden">
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.title')</div>
                            <div class="activity-element-info">
                                {!! getFirstNarrative($documentLink) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($documentLink['narrative'])])
                            </div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.category')</div>
                            <div class="activity-element-info">
                                @foreach($documentLink['category'] as $category)
                                    <li>{!! getCodeNameWithCodeValue('DocumentCategory' , $category['code'] , -5) !!}</li>
                                @endforeach
                            </div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.language')</div>
                            <div class="activity-element-info">{!! checkIfEmpty(getDocumentLinkLanguages($documentLink['language'])) !!}</div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.document_date')</div>
                            <div class="activity-element-info">{!! checkIfEmpty(formatDate(getVal($documentLink , ['document_date' , 0 , 'date']))) !!}</div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.recipient_country')</div>
                            @foreach($documentLink['recipient_country'] as $country)
                                <div class="activity-element-info">
                                    <li>{!! getCountryNameWithCode($country['code']) !!}</li>
                                    <div class="expanded">
                                        <div class="element-info">
                                            <div class="activity-element-label">
                                                @lang('activityView.narrative')</div>
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
        <a href="{{ url('/organization/' . $orgId . '/document-link') }}" class="edit-element">edit</a>
        <a href="{{ route('organization.delete-element',[$orgId,'document_link'])}}" class="delete pull-right">delete</a>
    </div>
@endif
