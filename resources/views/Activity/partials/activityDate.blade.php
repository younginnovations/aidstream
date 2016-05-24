@if(!emptyOrHasEmptyTemplate($activityDates))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.activity_date')</div>
        @foreach(groupActivityElements($activityDates , 'type') as $key => $groupedDates)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {{ $getCode->getCodeNameOnly('ActivityDateType', $key) }} @lang('activityView.date')
                </div>
                <div class="activity-element-info">
                    @foreach($groupedDates as $groupedDate)
                        <li>{{ formatDate($groupedDate['date']) }}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">{{ $getCode->getCodeNameOnly('ActivityDateType', $key) }} @lang('activityView.description')</div>
                                <div class="activity-element-info">{!! checkIfEmpty(checkIfEmpty(getFirstNarrative($groupedDate))) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($groupedDate['narrative'])])
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.activity-date.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'activity_date'])}}" class="delete pull-right">remove</a>
    </div>
@endif
