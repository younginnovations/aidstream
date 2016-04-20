@if(!emptyOrHasEmptyTemplate($budgets))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">
                Budgets
            </div>
            <a href="{{route('activity.budget.index', $id)}}" class="edit-element">edit</a>
            <a href="{{route('activity.delete-element', [$id, 'budget'])}}" class="delete pull-right">remove</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($budgets as $budget)
                <div class="panel-heading">
                    <div class="activity-element-title">{{(is_null($getCode->getActivityCodeName('BudgetType', $budget['budget_type']))?'': $getCode->getActivityCodeName('BudgetType', $budget['budget_type']) . ' ; '). $budget['value'][0]['amount'] .' '. $getCode->getCode('Activity', 'Currency', $budget['value'][0]['currency']) . ' ; '. formatDate($budget['value'][0]['value_date']) }}</div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Type:</div>
                                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('BudgetType', $budget['budget_type'])}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Period Start</div>
                            </div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Iso_date:</div>
                                    <div class="col-xs-12 col-sm-8">{{ formatDate($budget['period_start'][0]['date']) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Period End</div>
                            </div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Iso_date:</div>
                                    <div class="col-xs-12 col-sm-8">{{ formatDate($budget['period_end'][0]['date']) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Value</div>
                            </div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Amount:</div>
                                    <div class="col-xs-12 col-sm-8">{{$budget['value'][0]['amount']}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Currency:</div>
                                    <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('Currency', $budget['value'][0]['currency'])}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Date:</div>
                                    <div class="col-xs-12 col-sm-8">{{ formatDate($budget['value'][0]['value_date']) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
