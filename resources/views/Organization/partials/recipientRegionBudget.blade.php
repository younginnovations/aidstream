@if(!empty($recipient_region_budget))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">Recipient Region Budget</div>
            <a href="{{ url('/organization/' . $orgId . '/recipient-region-budget') }}" class="edit-element">edit</a>
        </div>
        <div class="panel-body row panel-level-2">
            @foreach($recipient_region_budget as $recipientRegionBudget)
                <div class="panel-heading">
                    <div class="activity-element-title">{{ $code->getActivityCodeName('BudgetStatus', $recipientRegionBudget['status'])}}</div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-body panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Status:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $code->getActivityCodeName('BudgetStatus', $recipientRegionBudget['status'])}}</div>
                                    </div>
                                    @foreach($recipientRegionBudget['recipient_region'] as $recipientRegion)
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Vocabulary</div>
                                            <div class="col-xs-12 col-xs-8">{{ $code->getActivityCodeName('RegionVocabulary', $recipientRegion['vocabulary']) }}</div>
                                        </div>
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Vocabulary Uri</div>
                                            <div class="col-xs-12 col-xs-8">{{ $recipientRegion['vocabulary_uri'] }}</div>
                                        </div>
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Code</div>
                                            <div class="col-xs-12 col-xs-8">{{ $code->getActivityCodeName('Region', $recipientRegion['code']) }}</div>
                                        </div>
                                        @foreach($recipientRegion['narrative'] as $recipientRegionNarrative)
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-xs-4">Text:</div>
                                                <div class="col-xs-12 col-xs-8">{{ $recipientRegionNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $recipientRegionNarrative['language']) }}</div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Period Start</div>
                                </div>
                                <div class="panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientRegionBudget['period_start'][0]['date']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Period End</div>
                                </div>
                                <div class="panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientRegionBudget['period_end'][0]['date']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Value</div>
                                </div>
                                <div class="panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Amount:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudget['value'][0]['amount']}}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Value Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientRegionBudget['value'][0]['value_date']) }}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Currency:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudget['value'][0]['currency']}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Budget Line</div>
                                </div>
                                <div class="panel-drop-body">
                                    @foreach($recipientRegionBudget['budget_line'] as $recipientRegionBudgetLine)
                                        <div class="panel panel-default">
                                            <div class="panel-body panel-element-body row">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Reference:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudgetLine['reference']}}</div>
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
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudgetLine['value'][0]['amount']}}</div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Value Date:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ formatDate($recipientRegionBudgetLine['value'][0]['value_date']) }}</div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Currency:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudgetLine['value'][0]['currency']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="activity-element-title">Narrative</div>
                                            </div>
                                            <div class="panel-element-body">
                                                @foreach($recipientRegionBudgetLine['narrative'] as $recipientRegionBudgetLineNarrative)
                                                    <div class="panel-body panel-element-body row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-xs-4">Text:</div>
                                                            <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudgetLineNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $recipientRegionBudgetLineNarrative['language']) }}</div>
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
                </div>
            @endforeach
        </div>
    </div>
@endif