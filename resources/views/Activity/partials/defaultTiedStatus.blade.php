@if(!empty($defaultTiedStatus))
    <div class="panel panel-default">
        <div class="panel-heading">Default Tied Status
            <a href="{{route('activity.default-tied-status.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-element-body row">
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-sm-4">Code: </div>
                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('TiedStatus', $defaultTiedStatus)}}</div>
            </div>
        </div>
    </div>
@endif
