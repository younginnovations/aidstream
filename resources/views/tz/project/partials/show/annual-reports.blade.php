@if ($project->annualReports())
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">
                Annual Reports
            </div>
            <div class="activity-element-info">
                <a href="{{ getVal($project->annualReports(), ['document_link', 'url']) }}">{{ getVal($project->annualReports(), ['document_link', 'title', 0, 'narrative', 0, 'narrative']) }}</a>
            </div>
        </div>
    </div>
@endif
