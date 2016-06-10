<div class="has-separate-delete">
    <div class="activity-element-wrapper">
        @if ($project->budget)
            <div></div>     {{-- place empty div to avoid hiding div below--}}
            <div class="activity-element-list">
                <div class="activity-element-label">
                    <span>Budget
                     <a href="{{ route('project.edit-budget', $project->id) }}" class="edit">
                         <span>Edit Budget</span>
                     </a>
                    </span>
                </div>
                @foreach ($project->budget as $budget)
                    <div class="activity-element-info">
                        <li>
                            <span>
                                {{ number_format(getVal($budget, ['value', 0, 'amount'])) }} {{ getVal($budget, ['value', 0, 'currency']) }}
                                [{{ formatDate(getVal($budget, ['period_start', 0, 'date'])) }}
                                - {{ formatDate(getVal($budget, ['period_end', 0, 'date'])) }}]

                                <span class="has-delete-wrap">
                                    {!! Form::open(['method' => 'POST', 'class' => 'delete', 'route' => ['project.budget.destroy', $project->id]]) !!}
                                    {!! Form::submit('Delete', ['class' => 'pull-left delete-transaction']) !!}
                                    {!! Form::close() !!}
                                 </span>

                            </span>
                        </li>
                    </div>
                @endforeach
            </div>
        @else
            <div class="activity-element-label">
                Budget
            </div>
            <div class="activity-element-list">
                <a href="{{ route('project.add-budget', $project->id) }}"
                   class="add-more"><span>Add Budget</span>
                </a>
            </div>
        @endif
    </div>
</div>
