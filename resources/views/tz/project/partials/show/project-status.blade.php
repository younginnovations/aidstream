<div class="activity-element-wrapper">
    <div class="activity-element-list">
        <div class="activity-element-label">
            Project Status
        </div>
        <div class="activity-element-info">
            {{ $getCode->getCodeListName('Activity','ActivityStatus', $project->activity_status) }}
        </div>
    </div>
</div>
