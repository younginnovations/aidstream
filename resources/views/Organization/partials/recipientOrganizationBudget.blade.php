@if(!empty($recipient_organization_budget))
    <div class="panel panel-default">
        <div class="panel-heading">Recipient Organization Budget
            <a href="#" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1 row">
            @foreach($recipient_organization_budget as $recipientOrgBudget)
                <div class="panel panel-default">
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">ref:</div>
                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['recipient_organization'][0]['ref'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    @foreach($recipientOrgBudget['narrative'] as $recipientOrgBudgetNarrative)
                        <div class="panel-heading">Narrative</div>
                        <div class="panel-body panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-xs-4">Text:</div>
                                <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetNarrative['narrative'] . ' [' . $recipientOrgBudgetNarrative['language'] . ']' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Value</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Text:</div>
                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['value'][0]['amount']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Value Date:</div>
                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['value'][0]['value_date'] }}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Currency:</div>
                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['value'][0]['currency']}}</div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Period Start</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Iso Date:</div>
                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['period_start'][0]['date'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Period End</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Iso Date:</div>
                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['period_end'][0]['date'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
                        <div class="panel-heading">Budget Line</div>
                        <div class="panel-body row">
                            @foreach($recipientOrgBudget['budget_line'] as $recipientOrgBudgetLine)
                                <div class="panel panel-default">
                                    <div class="panel-body panel-element-body row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Reference:</div>
                                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['reference']}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Value</div>
                                    <div class="panel-body panel-element-body row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Text:</div>
                                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['value'][0]['amount']}}</div>
                                        </div>
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Value Date:</div>
                                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['value'][0]['value_date'] }}</div>
                                        </div>
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Currency:</div>
                                            <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['value'][0]['currency']}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    @foreach($recipientOrgBudgetLine['narrative'] as $recipientOrgBudgetLineNarrative)
                                        <div class="panel-heading">Narrative</div>
                                        <div class="panel-body panel-element-body row">
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-xs-4">Text:</div>
                                                <div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLineNarrative['narrative'] . ' [' . $recipientOrgBudgetLineNarrative['language'] . ']' }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
