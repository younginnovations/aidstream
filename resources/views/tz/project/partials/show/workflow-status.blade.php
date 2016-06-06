<div class="activity-status activity-status-{{ $statusLabel[$activityWorkflow] }}">
    <ol>
        @foreach($statusLabel as $key => $value)
            @if($key == $activityWorkflow)
                <li class="active"><span>{{ $value }}</span></li>
            @else
                <li><span>{{ $value }}</span></li>
            @endif
        @endforeach
    </ol>
    @include('tz.project.partials.workflow')
</div>
