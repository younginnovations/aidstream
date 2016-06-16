@foreach ($project->activity_date as $date)
    @if(getVal($date, ['type']) == 2)
        <div class="activity-element-wrapper">
            <div class="activity-element-list">
                <div class="activity-element-label">
                    Start Date
                </div>
                <div class="activity-element-info">
                    {{ $date['date'] }}
                </div>
            </div>
        </div>
    @endif

    @if(getVal($date, ['type']) == 4)
        <div class="activity-element-wrapper">
            <div class="activity-element-list">
                <div class="activity-element-label">
                    End Date
                </div>
                <div class="activity-element-info">
                    {{ getVal($date, ['date']) }}
                </div>
            </div>
        </div>
    @endif
@endforeach
