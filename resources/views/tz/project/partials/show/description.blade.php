<div class="activity-element-wrapper">
    @foreach ($project->description as $description)
        @if(getVal($description, ['type']) == 1)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    General Description
                </div>
                <div class="activity-element-info">
                    {{$description['narrative'][0]['narrative']}}
                </div>
            </div>
        @endif

        @if(getVal($description, ['type']) == 2)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    Objectives
                </div>
                <div class="activity-element-info">
                    {{$description['narrative'][0]['narrative']}}
                </div>
            </div>
        @endif

        @if(getVal($description, ['type']) == 3)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    Target Groups
                </div>
                <div class="activity-element-info">
                    {{$description['narrative'][0]['narrative']}}
                </div>
            </div>
        @endif
    @endforeach
</div>
