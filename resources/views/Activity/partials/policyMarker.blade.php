@if(!emptyOrHasEmptyTemplate($policyMarkers))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.policy_marker')</div>
        @foreach(groupPolicyMarkerElement($policyMarkers) as $key => $policyMarkers)
            <div class="activity-element-list">
                <div class="activity-element-label">{{$key}}</div>
                <div class="activity-element-info">
                    @foreach($policyMarkers as $policyMarker)
                        <li>{{ $policyMarker['policy_marker'] .' - '. $getCode->getCodeNameOnly('PolicyMarker' , $policyMarker['policy_marker']) }}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            @if(session('version') != 'V201')
                                <div class="element-info">
                                    <div class="activity-element-label">@lang('activityView.vocabulary_uri')</div>
                                    <div class="activity-element-info">{!! getClickableLink(getVal($policyMarker , ['vocabulary_uri'])) !!}</div>
                                </div>
                            @endif
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.significance')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('PolicySignificance' , $policyMarker['significance'] , -4) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.description')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative($policyMarker) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($policyMarker['narrative'])])
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.policy-marker.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'policy_marker'])}}" class="delete pull-right">remove</a>
    </div>
@endif
