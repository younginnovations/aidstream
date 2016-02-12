@if(!empty($activityStatus))
    <div class="panel panel-default">
        <div class="panel-heading">Activity Status
            <a href="{{route('activity.activity-status.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-element-body row">
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-sm-4">Code: </div>
                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('ActivityStatus', $activityStatus[0])}}</div>
            </div>
        </div>
    </div>
@endif
