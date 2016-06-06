@if ($project->resultDocuments())
    <div class="activity-element-wrapper">
        <div class="title">

        </div>
        <div class="activity-element-list">
            <div class="activity-element-label">
                Results/Outcomes Documents
            </div>
            <div class="activity-element-info">
                <a href="{{ getVal($project->resultDocuments(), ['document_link', 'url']) }}">{{ getVal($project->resultDocuments(), ['document_link', 'title', 0, 'narrative', 0, 'narrative']) }}</a>
            </div>
        </div>
    </div>
@endif
