@if(!empty($budgets))
    <div class="panel panel-default">
        <div class="panel-heading">Budgets
            <a href="{{route('activity.budget.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($budgets as $budget)
                <div class="panel panel-default">
                    <div class="panel-heading">{{$getCode->getActivityCodeName('BudgetType', $budget['budget_type']) . ' ; [USD] '. $budget['value'][0]['amount'] . ' ; '. formatDate($budget['value'][0]['value_date']) }}</div>
                    <div class="panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Type:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('BudgetType', $budget['budget_type'])}}</div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Period Start</div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Iso_date:</div>
                                    <div class="col-xs-12 col-sm-8">{{ formatDate($budget['period_start'][0]['date']) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Period End</div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Iso_date:</div>
                                    <div class="col-xs-12 col-sm-8">{{ formatDate($budget['period_end'][0]['date']) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Value</div>
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
