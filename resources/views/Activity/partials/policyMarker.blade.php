@if(!emptyOrHasEmptyTemplate($policyMarkers))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">
                Policy Markers
            </div>
            <a href="{{route('activity.policy-marker.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($policyMarkers as $policyMarker)
                <div class="panel-heading">
                    <div class="activity-element-title">
                        {{$getCode->getActivityCodeName('PolicyMarker', $policyMarker['policy_marker'])}}
                    </div>
                </div>
                <div class="panel-body row">
                    <div class="panel-element-body">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Significance:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('PolicySignificance', $policyMarker['significance'])}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Policy Marker:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('PolicyMarker', $policyMarker['policy_marker'])}}</div>
                        </div>
                        @foreach($policyMarker['narrative'] as $narrative)
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Text:</div>
                                <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language'])}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
