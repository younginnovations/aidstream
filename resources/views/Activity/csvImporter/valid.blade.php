<div class="panel panel-default">
    @if ($activities)
        @foreach ($activities as $index => $activity)
            <div class="panel-heading">
                <label>
                    <span class="panel-content-heading">
                        <h3>
                            <input type="checkbox" value="{{ $index }}" name="activities[]"/>
                            <span></span>
                            <span class="panel-title">
                                {{ getVal($activity, ['data', 'identifier', 'activity_identifier'], '') }} - {{ getVal($activity, ['data', 'title', 0, 'narrative']) }}
                            </span>
                        </h3>
                    </span>
                </label>
            </div>
        @endforeach
    @endif
</div>
