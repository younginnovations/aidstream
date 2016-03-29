@if(!emptyOrHasEmptyTemplate($locations))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">
                Location
            </div>
            <a href="{{route('activity.location.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($locations as $location)
                <div class="panel-heading">
                    <div class="activity-element-title">
                        {{$getCode->getActivityCodeName('GeographicLocationReach', $location['location_reach'][0]['code'])}}
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Reference:</div>
                                <div class="col-xs-12 col-sm-8">{{$location['reference']}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Location Reach</div>
                            </div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('GeographicLocationReach', $location['location_reach'][0]['code'])}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Location Id</div>
                            </div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$location['location_id'][0]['code']}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Vocabulary:</div>
                                    <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('GeographicVocabulary', $location['location_id'][0]['vocabulary'])}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Name</div>
                            </div>
                            <div class="panel-element-body row">
                                @foreach($location['name'][0]['narrative'] as $narrative)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Description</div>
                            </div>
                            <div class="panel-element-body row">
                                @foreach($location['location_description'][0]['narrative'] as $narrative)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Activity Description</div>
                            </div>
                            <div class="panel-element-body row">
                                @foreach($location['activity_description'][0]['narrative'] as $narrative)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Administrative</div>
                            </div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$location['administrative'][0]['code']}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Administrative:</div>
                                    <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('GeographicVocabulary', $location['administrative'][0]['vocabulary'])}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Level:</div>
                                    <div class="col-xs-12 col-sm-8">{{$location['administrative'][0]['level']}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Point</div>
                            </div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Srs Name:</div>
                                    <div class="col-xs-12 col-sm-8">{{$location['point'][0]['srs_name']}}</div>
                                </div>
                                @foreach($location['point'][0]['position'] as $position)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                        <div class="col-xs-12 col-sm-8">{{$position['latitude'] . ' , '. $position['longitude']}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Exactness</div>
                            </div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('GeographicExactness',$location['exactness'][0]['code'])}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Location Class</div>
                            </div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('GeographicLocationClass',$location['location_class'][0]['code'])}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Feature Designation</div>
                            </div>
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('LocationType', $location['feature_designation'][0]['code'])}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
