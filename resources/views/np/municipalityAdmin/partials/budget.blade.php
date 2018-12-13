@if ($activity->budget)
<div class="activity__detail" id="activity__budget">
    <div>
    </div>
    <div class="activity__element__list">
        <h3>
            @lang('lite/title.budget')
        </h3>
        <div class="activity__element--info">
            @foreach ($activity->budget as $index => $budget)
                <li>
                    <span>
                    {{ number_format(round(getVal($budget, ['value', 0, 'amount']),2)) }}
                    @if(getVal($budget, ['value', 0, 'currency']))
                        {{ getVal($budget, ['value', 0, 'currency']) }}
                    @else
                        {{ $defaultCurrency }}
                    @endif
                    [{{ formatDate(getVal($budget, ['period_start', 0, 'date'])) }}
                    - {{ formatDate(getVal($budget, ['period_end', 0, 'date'])) }}]
                    </span>
                </li>
            @endforeach
        </div>
    </div>
</div>
@endif

