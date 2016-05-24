@if(!emptyOrHasEmptyTemplate($otherIdentifiers))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.other_identifier')</div>
        @foreach(groupActivityElements($otherIdentifiers , 'type') as $key => $groupedIdentifiers)
            <div class="activity-element-list">
                <div class="activity-element-label">{{$key}} @lang('activityView.rep_org_internal_acitivity_identifier')</div>
                <div class="activity-element-info">
                    @foreach($groupedIdentifiers as $identifiers)
                        <li>{{$identifiers['reference']}}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.owner_org_reference')</div>
                                <div class="activity-element-info">{!! checkIfEmpty($identifiers['owner_org'][0]['reference']) !!}</div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.owner_org_name')</div>
                                <div class="activity-element-info">{!! checkIfEmpty(getFirstNarrative($identifiers['owner_org'][0])) !!}
                                @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages(getOwnerNarrative($identifiers))])
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.other-identifier.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'other_identifier'])}}" class="delete pull-right" data-toggle="tooltip" title="delete other identifier-">remove</a>
    </div>
@endif
