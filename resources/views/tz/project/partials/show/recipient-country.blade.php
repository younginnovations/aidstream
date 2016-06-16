<div class="activity-element-wrapper">
    <div class="activity-element-list">
        <div class="activity-element-label">
            Recipient Country
        </div>
        <div class="activity-element-info">
            {{ $getCode->getCodeListName('Organization','Country', $project->recipient_country[0]['country_code']) }}
        </div>
    </div>
</div>
