@if(!emptyOrHasEmptyTemplate($locations))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.location') @if(array_key_exists('Location',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach(getLocationReach($locations) as $key => $locations)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {{ $key }}
                </div>
                <div class="activity-element-info">
                    @foreach($locations as $location)
                        <li>
                            {!!  getFirstNarrative($location['name'][0]) !!}
                            @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($location['name'][0]['narrative'])])
                        </li>
                        <div class="toggle-btn">
                            <span class="show-more-info">@lang('global.show_more_info')</span>
                            <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.location_reference')</div>
                                <div class="activity-element-info">{!! checkIfEmpty($location['reference']) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.location_id_vocabulary')</div>
                                <div class="activity-element-info">
                                    @foreach($location['location_id'] as $locationId)
                                        <li>{!!  getLocationIdVocabulary($locationId)  !!}</li>
                                    @endforeach
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.location_description')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative($location['location_description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($location['location_description'][0]['narrative'])])
                                </div>

                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.activity_description')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative($location['activity_description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($location['activity_description'][0]['narrative'])])
                                </div>

                            </div>
                            <div class="element-info">
                                <div class="activity-element-label"> @lang('elementForm.administrative_vocabulary')</div>
                                <div class="activity-element-info">
                                    @foreach($location['administrative'] as $locationAdministrative)
                                        <li>{!! getAdministrativeVocabulary($locationAdministrative) !!}</li>
                                    @endforeach
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.point')</div>
                                <div class="activity-element-info">{!! getLocationPoint($location) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.exactness')</div>
                                <div class="activity-element-info">{!! getLocationPropertiesValues($location , 'exactness' ,'GeographicExactness' ) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.location_class')</div>
                                <div class="activity-element-info">{!! getLocationPropertiesValues($location , 'location_class' ,'GeographicLocationClass' ) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.feature_designation')</div>
                                <div class="activity-element-info">{!! getLocationPropertiesValues($location , 'feature_designation' ,'LocationType' , -6) !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.location.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        <a href="{{route('activity.delete-element', [$id, 'location'])}}" class="delete pull-right">@lang('global.remove')</a>
    </div>
@endif
