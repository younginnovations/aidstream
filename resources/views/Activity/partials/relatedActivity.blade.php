@if(!empty($relatedActivities))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="activity-element-title">
                Related Activity
            </div>
            <a href="{{route('activity.related-activity.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($relatedActivities as $relatedActivity)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="activity-element-title">
                            {{$relatedActivity['activity_identifier']}}
                        </div>
                    </div>
                    <div class="panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Ref:</div>
                            <div class="col-xs-12 col-sm-8">{{$relatedActivity['activity_identifier']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Type:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('RelatedActivityType', $relatedActivity['relationship_type'])}}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
