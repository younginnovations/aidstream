@if(!empty($capitalSpend))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="activity-element-title">
                Capital Spend
            </div>
            <a href="{{route('activity.capital-spend.index', $id)}}" class="edit-element">edit</a>
            <a href="{{route('activity.delete-element', [$id, 'capital_spend'])}}" class="delete pull-right">remove</a>
        </div>
        <div class="panel-body panel-element-body row">
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-sm-4">Percentage:</div>
                <div class="col-xs-12 col-sm-8">{{$capitalSpend}}</div>
            </div>
        </div>
    </div>
@endif
