@if ($activity->budget)
<div class="activity__detail" id="activity__budget">
    <div>
    </div>
    <div class="activity__element__list">
        <h3>
            @lang('lite/title.budget')
            <a href="{{ route('np.activity.budget.edit', $activity->id) }}"
               class="edit-activity" title="Edit">@lang('lite/elementForm.edit_budget')
            </a>
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

                    <a data-href="{{ route('np.activity.budget.delete', $activity->id)}}" data-index="{{ $index }}"
                       class="delete-activity delete-confirm" data-toggle="modal" data-target="#delete-modal" data-message="@lang('lite/global.confirm_delete')"> @lang('lite/global.delete') </a>
                        </span>
                </li>
            @endforeach
        </div>
        <a href="{{ route('np.activity.budget.create', $activity->id) }}"
           class="add-more"><span>@lang('lite/elementForm.add_more_budget')</span></a>
    </div>
</div>
@endif

