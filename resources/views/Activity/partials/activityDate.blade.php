@if(!empty($activityDates))
    <div class="panel panel-default">
        <div class="panel-heading">Activity Date
            <a href="{{route('activity.activity-date.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($activityDates as $activity_date)
                <div class="panel panel-default">
                    <div class="panel-heading">{{$getCode->getActivityCodeName('ActivityDateType', $activity_date['type']) . ' ; ' . formatDate($activity_date['date']) }}</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Type:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('ActivityDateType', $activity_date['type'])}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Date:</div>
                            <div class="col-xs-12 col-sm-8">{{ formatDate($activity_date['date']) }}</div>
                        </div>
                        @foreach($activity_date['narrative'] as $narrative)
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Text:</div>
                                <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' [' . $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
