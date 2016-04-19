@if(!empty($defaultFinanceType))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="activity-element-title">
                Default Finance Type
            </div>
            <a href="{{route('activity.default-finance-type.index', $id)}}" class="edit-element">edit</a>
            <a href="{{route('activity.delete-element', [$id, 'default_finance_type'])}}" class="delete pull-right">remove</a>
        </div>
        <div class="panel-body panel-element-body row">
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-sm-4">Code:</div>
                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('FinanceType', $defaultFinanceType)}}</div>
            </div>
        </div>
    </div>
@endif
