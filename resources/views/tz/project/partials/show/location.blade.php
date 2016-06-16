<div class="activity-element-wrapper">
    <div class="activity-element-list">
        <div class="activity-element-label">
            Location
        </div>
        <div class="activity-element-info">
            @foreach ($project->location as $location)
                @if (getVal($location, ['administrative', 0, 'code']))
                    <li>
                        {{ getVal($location, ['administrative', 0, 'code']) }}, {{ getVal($location, ['administrative', 1, 'code']) }}
                    </li>
                @endif
            @endforeach
        </div>
    </div>
</div>
