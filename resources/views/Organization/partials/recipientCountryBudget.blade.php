@if(!empty($recipient_country_budget))
    <div class="panel panel-default">
        <div class="panel-heading">Recipient Country
            <a href="{{ url('/organization/' . $orgId . '/recipient-country-budget') }}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-element-body row">
            @foreach($recipient_country_budget as $recipientCountryBudget)
                <div class="panel panel-default">
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Code:</div>
                            <div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('Country', $recipientCountryBudget['recipient_country'][0]['code'])}}</div>
                        </div>
                        @foreach($recipientCountryBudget['recipient_country'][0]['narrative'] as $recipientCountryNarrative)
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-xs-4">Text:</div>
                                <div class="col-xs-12 col-xs-8">{{ $recipientCountryNarrative['narrative'] . ' [' . $recipientCountryNarrative['language'] . ']' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Value</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Text:</div>
                            <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudget['value'][0]['amount']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Value Date:</div>
                            <div class="col-xs-12 col-xs-8">{{ formatDate($recipientCountryBudget['value'][0]['value_date']) }}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Currency:</div>
                            <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudget['value'][0]['currency']}}</div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Period Start</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Iso Date:</div>
                            <div class="col-xs-12 col-xs-8">{{ formatDate($recipientCountryBudget['period_start'][0]['date']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Period End</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Iso Date:</div>
                            <div class="col-xs-12 col-xs-8">{{ formatDate($recipientCountryBudget['period_end'][0]['date']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Budget Line</div>
                    <div class="panel-body panel-element-body row">
                        @foreach($recipientCountryBudget['budget_line'] as $recipientCountryBudgetLine)
                            <div class="panel panel-default">
                                <div class="panel-body panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Reference:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLine['reference']}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">Value</div>
                                <div class="panel-body panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Text:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLine['value'][0]['amount']}}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Value Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientCountryBudgetLine['value'][0]['value_date']) }}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Currency:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLine['value'][0]['currency']}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                @foreach($recipientCountryBudgetLine['narrative'] as $recipientCountryBudgetLineNarrative)
                                    <div class="panel-heading">Narrative</div>
                                    <div class="panel-body panel-element-body row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Text:</div>
                                            <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLineNarrative['narrative'] . ' [' . $recipientCountryBudgetLineNarrative['language'] . ']' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
