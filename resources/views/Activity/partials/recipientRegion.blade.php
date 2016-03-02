@if(!empty($recipientRegions))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="activity-element-title">
                Recipient Region
            </div>
            <a href="{{route('activity.recipient-region.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($recipientRegions as $recipientRegion)
                <div class="panel panel-default">
                    <div class="panel-heading">{{$getCode->getActivityCodeName('Region', $recipientRegion['region_code']) . ' ; ' . $recipientRegion['percentage']}}</div>
                    <div class="panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Percentage:</div>
                            <div class="col-xs-12 col-sm-8">{{$recipientRegion['percentage']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Code:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('Region', $recipientRegion['region_code'])}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Vocabulary:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('RegionVocabulary', $recipientRegion['region_vocabulary'])}}</div>
                        </div>
                        @foreach($recipientRegion['narrative'] as $narrative)
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Text:</div>
                                <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
