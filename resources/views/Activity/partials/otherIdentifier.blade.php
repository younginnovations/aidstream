@if(!emptyOrHasEmptyTemplate(getVal($activityDataList, ['other_identifier'], [])))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.other_identifier') @if(array_key_exists('Other Identifier',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach(groupActivityElements(getVal($activityDataList, ['other_identifier'], []) , 'type') as $key => $groupedIdentifiers)
            <div class="activity-element-list">
                <div class="activity-element-label col-md-4">{{$key}} @lang('elementForm.reporting_org_internal_activity_identifier')</div>
                <div class="activity-element-info">
                    @foreach($groupedIdentifiers as $identifiers)
                        <li>{{ getVal($identifiers, ['reference']) }}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">@lang('global.show_more_info')</span>
                            <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.owner_org_reference')</div>
                                <div class="activity-element-info">{!! checkIfEmpty(getVal($identifiers, ['owner_org', 0, 'reference'])) !!}</div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.owner_org_name')</div>
                                <div class="activity-element-info">{!! checkIfEmpty(getFirstNarrative(getVal($identifiers, ['owner_org', 0]))) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages(getOwnerNarrative($identifiers))])
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.other-identifier.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        @include('Activity.partials.element-delete-form', ['element' => 'other_identifier', 'id' => $id])
    </div>
@endif
