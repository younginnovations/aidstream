<div class="panel panel-default">
    @if ($results)
        @foreach ($results as $index => $result)
            <div class="panel-heading">
                <label>
                    <span class="panel-content-heading">
                        <h3>
                            <input type="checkbox" disabled="disabled" value="{{ $index }}"/>
                            <span></span>
                        <span class="panel-title">
                            {{ getVal($result, ['data', 'title', 0, 'narrative', 0, 'narrative'], '') }}
                            - {{ getVal($result, ['data', 'description', 0, 'narrative', 0, 'narrative']) }}
                        </span>
                        </h3>
                    </span>
                    <ul class="data-listing">
                        @foreach (getVal($result, ['errors'], []) as $error)
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