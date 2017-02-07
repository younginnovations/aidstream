<div class="panel panel-default">
    @if ($activities)
        @foreach ($activities as $index => $activity)
            <div class="panel-heading {{ getVal($activity, ['existence']) ? 'existent-data' : 'new-data' }}">
                <label>
                    <span class="panel-content-heading">
                        <h3>
                            <input type="checkbox" checked class="{{ getVal($activity, ['existence']) ? 'existence' : 'new' }}" value="{{ $index }}" name="activities[]"/>
                            <span></span>
                            <span class="panel-title">
                                {{ getVal($activity, ['data', 'identifier', 'activity_identifier'], '') }} - {{ getVal($activity, ['data', 'title', 0, 'narrative']) }}
                                @if(getVal($activity, ['existence']))
                                    (Existing)
                                @else
                                    (New)
                                @endif
                            </span>
                        </h3>
                    </span>
                </label>
            </div>
        @endforeach
    @endif
</div>
