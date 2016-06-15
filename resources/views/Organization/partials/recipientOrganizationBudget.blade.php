@if(!emptyOrHasEmptyTemplate($recipient_organization_budget))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">Recipient Organization Budget</div>
            <a href="{{ route('organization.recipient-organization-budget.index', $orgId)}}"
               class="edit-element">edit</a>
            <a href="{{ route('organization.delete-element', [$orgId,'recipient_organization_budget'])}}" class="delete pull-right">delete</a>
        </div>
        <div class="panel-body panel-level-2">
            @foreach($recipient_organization_budget as $recipientOrgBudget)
                <div class="panel-heading">
                    <div class="activity-element-title">
                        Ref : {{ $recipientOrgBudget['recipient_organization'][0]['ref'] }}
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-body panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Ref:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['recipient_organization'][0]['ref'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                @foreach($recipientOrgBudget['recipient_organization'][0]['narrative'] as $recipientOrgBudgetNarrative)
                                    <div class="panel-heading">
                                        <div class="activity-element-title">Narrative</div>
                                    </div>
                                    <div class="panel-element-body">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Text:</div>
                                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $recipientOrgBudgetNarrative['language']) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Value</div>
                                </div>
                                <div class="panel-element-body">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Text:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['value'][0]['amount']}}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Value Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientOrgBudget['value'][0]['value_date']) }}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Currency:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['value'][0]['currency']}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Period Start</div>
                                </div>
                                <div class="panel-element-body">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientOrgBudget['period_start'][0]['date']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Period End</div>
                                </div>
                                <div class="panel-element-body">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientOrgBudget['period_end'][0]['date']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-heading">
                                <div class="activity-element-title">Budget Line</div>
                            </div>
                            <div class="panel-body">
                                @foreach($recipientOrgBudget['budget_line'] as $recipientOrgBudgetLine)
                                    <div class="panel-heading">
                                        <div class="activity-element-title">{{ $recipientOrgBudgetLine['reference']}}</div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="panel panel-default">
                                            <div class="panel-body panel-element-body row">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Reference:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['reference']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="activity-element-title">Value</div>
                                            </div>
                                            <div class="panel-element-body row">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Text:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['value'][0]['amount']}}</div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Value Date:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ formatDate($recipientOrgBudgetLine['value'][0]['value_date']) }}</div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Currency:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['value'][0]['currency']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            @foreach($recipientOrgBudgetLine['narrative'] as $recipientOrgBudgetLineNarrative)
                                                <div class="panel-heading">
                                                    <div class="activity-element-title">Narrative</div>
                                                </div>
                                                <div class="panel-element-body row">
                                                    <div class="col-xs-12 col-md-12">
                                                        <div class="col-xs-12 col-xs-4">Text:</div>
                                                        <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLineNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $recipientOrgBudgetLineNarrative['language'])}}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
