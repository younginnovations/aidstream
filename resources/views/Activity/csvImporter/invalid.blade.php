<div class="panel panel-default">
    @if ($activities)
        @foreach ($activities as $index => $activity)
            <div class="panel-heading">
                <label>
                    <span class="panel-content-heading">
                        <h3>
                            <input type="checkbox" disabled="disabled" value="{{ $index }}"/>
                            <span></span>
                        <span class="panel-title">
                            {{ getVal($activity, ['data', 'identifier', 'activity_identifier'], '') }}
                            - {{ getVal($activity, ['data', 'title', 0, 'narrative']) }}
                        </span>
                        </h3>
                    </span>
                    <ul class="data-listing">
                        @foreach (getVal($activity, ['errors'], []) as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </label>
            </div>
        @endforeach
    @endif
</div>
