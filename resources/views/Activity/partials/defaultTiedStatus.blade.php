@if(!empty($defaultTiedStatus))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="activity-element-title">
                Default Tied Status
            </div>
            <a href="{{route('activity.default-tied-status.index', $id)}}" class="edit-element">edit</a>
            <a href="{{route('activity.delete-element', [$id, 'default_tied_status'])}}" class="delete pull-right">remove</a>
        </div>
        <div class="panel-body panel-element-body row">
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-sm-4">Code:</div>
                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('TiedStatus', $defaultTiedStatus)}}</div>
            </div>
        </div>
    </div>
@endif
