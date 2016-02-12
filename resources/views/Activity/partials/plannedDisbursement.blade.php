@if(!empty($plannedDisbursements))
    <div class="panel panel-default">
        <div class="panel-heading">Planned Disbursements
            <a href="{{route('activity.planned-disbursement.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($plannedDisbursements as $plannedDisbursement)
                <div class="panel panel-default">
                    <div class="panel-heading">{{'[USD]'. $plannedDisbursement['value'][0]['amount'] . ' ; '. date('M d, Y', strtotime($plannedDisbursement['value'][0]['value_date'])) }}</div>
                    <div class="panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Type: </div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('BudgetType', $plannedDisbursement['planned_disbursement_type'])}}</div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Period Start</div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Iso_date: </div>
                                    <div class="col-xs-12 col-sm-8">{{ date('M d, Y', strtotime($plannedDisbursement['period_start'][0]['date'])) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Period End</div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Iso_date: </div>
                                    <div class="col-xs-12 col-sm-8">{{ date('M d, Y', strtotime($plannedDisbursement['period_end'][0]['date'])) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Value</div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Amount: </div>
                                    <div class="col-xs-12 col-sm-8">{{$plannedDisbursement['value'][0]['amount']}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Currency: </div>
                                    <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('Currency', $plannedDisbursement['value'][0]['currency'])}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Date: </div>
                                    <div class="col-xs-12 col-sm-8">{{ date('M d, Y', strtotime($plannedDisbursement['value'][0]['value_date'])) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
