<div class="panel panel-default">
    @if ($results)
        @foreach ($results as $index => $result)
            <div class="panel-heading">
                <label>
                    <span class="panel-content-heading">
                        <h3>
                            <input type="checkbox" value="{{ $index }}" name="results[]"/>
                            <span></span>
                            <span class="panel-title">
                                {{ getVal($result, ['data', 'title', 0, 'narrative', 0, 'narrative'], '') }}
                                - {{ getVal($result, ['data', 'description', 0, 'narrative', 0, 'narrative']) }}
                            </span>
                        </h3>
                    </span>
                </label>
            </div>
        @endforeach
    @endif
</div>
