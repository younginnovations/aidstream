<div class="panel__status text-center">
    <div class="col-sm-4">
        <h2>@lang('lite/activityDashboard.total_activities')</h2>
        <span class="count">
                {{count($activities)}}
                </span>
        <div class="published-num">
            <span>@lang('lite/activityDashboard.no_of_activities_published_to_iati'):</span>
            @if($noOfPublishedActivities > 0)
                <a href="{{ url('who-is-using/' . str_replace('/', '-', $orgIdentifier) . '?published=true') }}">{{ $noOfPublishedActivities }}</a>
            @else
                0
            @endif
        </div>
    </div>
    <div class="col-sm-4">
        <h2>@lang('lite/activityDashboard.activities_by_status')</h2>
        <div class="stats">
            <div class="background-masker header-top"></div>
            <div class="background-masker header-left"></div>
            <div class="background-masker header-right"></div>
            <div class="background-masker header-bottom"></div>
            <div class="background-masker subheader-left"></div>
            <div class="background-masker subheader-right"></div>
            <div class="background-masker subheader-bottom"></div>
            <div class="background-masker content-top"></div>
            <div class="background-masker content-first-end"></div>
            <div class="background-masker content-second-line"></div>
            <div class="background-masker content-second-end"></div>
            <div class="background-masker content-third-line"></div>
            <div class="background-masker content-third-end"></div>
        </div>
    </div>
    <div class="col-sm-4">
        <h2>@lang('lite/activityDashboard.total_budget')</h2>
        <span class="count" id="budgetTotal"><small>$</small><span id="totalBudget">0</span><small id="placeValue"></small></span>
        <div class="highest-budget">@lang('lite/activityDashboard.highest_budget_in_activity'): <span
                    id="maxBudget">$0</span></div>
    </div>
</div>