@if(!emptyOrHasEmptyTemplate($recipientRegions))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.recipient_region')</div>
            <div class="activity-element-info">
                @foreach($recipientRegions as $recipientRegion)
                    <li>{!! getRecipientInformation($recipientRegion['region_code'], $recipientRegion['percentage'], 'Region') !!}</li>
                    <div class="toggle-btn">
                        <span class="show-more-info">Show more info</span>
                        <span class="hide-more-info hidden">Hide more info</span>
                    </div>
                    <div class="more-info hidden">
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.region_vocabulary')</div>
                            <div class="activity-element-info">{{ $recipientRegion['region_vocabulary'] . '-' . substr($getCode->getActivityCodeName('RegionVocabulary', $recipientRegion['region_vocabulary']) , 0 , -4) }}</div>
                        </div>
                        @if(session('version') != 'V201')
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.vocabulary_uri')</div>
                                <div class="activity-element-info">{!!  getClickableLink($recipientRegion['vocabulary_uri']) !!}</div>
                            </div>
                        @endif
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.description')</div>
                            <div class="activity-element-info">
                                {!! getFirstNarrative($recipientRegion) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($recipientRegion['narrative'])])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <a href="{{route('activity.recipient-region.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'recipient_region'])}}" class="delete pull-right">remove</a>
    </div>
@endif
