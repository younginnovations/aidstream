@if(!empty($defaultAidType))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="activity-element-title">
                Default Aid Type
            </div>
            <a href="{{route('activity.default-aid-type.index', $id)}}" class="edit-element">edit</a>
            <a href="{{route('activity.delete-element', [$id, 'default_aid_type'])}}" class="delete pull-right">remove</a>
        </div>
        <div class="panel-body panel-element-body row">
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-sm-4">Code:</div>
                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('AidType', $defaultAidType)}}</div>
            </div>
        </div>
    </div>
@endif
