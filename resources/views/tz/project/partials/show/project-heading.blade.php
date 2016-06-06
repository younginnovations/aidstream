<div>
    <span>
        {{ $project->title ? $project->title[0]['narrative'] : 'No Title' }}
        <a href="{{ route('project.edit', $project->id) }}" class="pull-right" style="font-size: 12px;">Edit</a>
        <br>
        <a href="{{ route('change-project-defaults', $id) }}" class="pull-right" style="font-size: 12px;">
            Override Default Values
        </a>
    </span>
    <div class="element-panel-heading-info">
        <span>{{ $project->identifier['activity_identifier'] }}</span>
        <span class="last-updated-date">Last Updated on: {{ changeTimeZone($project['updated_at'], 'M d, Y H:i') }}</span>
    </div>
</div>

