@if(!empty($activityScope))
    <div class="panel panel-default">
        <div class="panel-heading">Activity Scope
            <a href="{{route('activity.activity-scope.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-element-body">
            <div class="col-xs-12 col-md-12 clearfix">
                <div class="col-xs-12 col-sm-4">Code: </div>
                <div class="col-xs-12 col-sm-8">{{ $getCode->getActivityCodeName('ContactType', $activityScope) }}</div>
            </div>
        </div>
    </div>
@endif
