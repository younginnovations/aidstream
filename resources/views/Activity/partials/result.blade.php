@if(!empty($results))
    <div class="panel panel-default">
        <div class="panel-heading">Results
            <a href="{{route('activity.result.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($results as $result)
                <div class="panel panel-default">
                    <div class="panel-heading">{{$getCode->getActivityCodeName('ResultType', $result['result']['type'])}}</div>
                    <div class="panel panel-default">
                        <div class="panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Type:</div>
                                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('ResultType', $result['result']['type'])}}</div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Aggregation Status:</div>
                                <div class="col-xs-12 col-sm-8">{{($result['result']['aggregation_status'] == "1") ? 'True' : 'False' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Title</div>
                            <div class="panel-body row">
                                @foreach($result['result']['title'] as $title)
                                    @foreach($title['narrative'] as $narrative)
                                        <div class="panel-element-body row">
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-sm-4">Text:</div>
                                                <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Description</div>
                            <div class="panel-body row">
                                @foreach($result['result']['description'] as $description)
                                    @foreach($description['narrative'] as $narrative)
                                        <div class="panel-element-body row">
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-sm-4">Text:</div>
                                                <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Indicator</div>
                            <div class="panel-body row">
                                @foreach($result['result']['indicator'] as $indicator)
                                    <div class="panel panel-default">
                                        <div class="panel-element-body row">
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-sm-4">Measure:</div>
                                                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('IndicatorMeasure', $indicator['measure'])}}</div>
                                            </div>
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-sm-4">Ascending:</div>
                                                <div class="col-xs-12 col-sm-8">{{($indicator['ascending'] == "1") ? 'True' : 'False' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Title</div>
                                        <div class="panel-body row">
                                            @foreach($indicator['title'] as $title)
                                                @foreach($title['narrative'] as $narrative)
                                                    <div class="panel-element-body row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-sm-4">Text:</div>
                                                            <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Description</div>
                                        <div class="panel-body row">
                                            @foreach($indicator['description'] as $description)
                                                @foreach($description['narrative'] as $narrative)
                                                    <div class="panel-element-body row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-sm-4">Text:</div>
                                                            <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Baseline</div>
                                        <div class="panel-body row">
                                            @foreach($indicator['baseline'] as $baseline)
                                                <div class="panel panel-default">
                                                    <div class="panel-element-body row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-sm-4">Year:</div>
                                                            <div class="col-xs-12 col-sm-8">{{$baseline['year']}}</div>
                                                        </div>
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-sm-4">Value:</div>
                                                            <div class="col-xs-12 col-sm-8">{{$baseline['value']}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Comment</div>
                                                    <div class="panel-body row">
                                                        @foreach($baseline['comment'] as $comment)
                                                            @foreach($comment['narrative'] as $narrative)
                                                                <div class="panel-element-body row">
                                                                    <div class="col-xs-12 col-md-12">
                                                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Period</div>
                                        <div class="panel-body row panel-level-3">
                                            @foreach($indicator['period'] as $period)
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Period Start</div>
                                                    <div class="panel-element-body row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-sm-4">Iso_date:</div>
                                                            <div class="col-xs-12 col-sm-8">{{ formatDate($period['period_start'][0]['date']) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Period End</div>
                                                    <div class="panel-element-body row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-sm-4">Iso_date:</div>
                                                            <div class="col-xs-12 col-sm-8">{{ formatDate($period['period_end'][0]['date']) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Target</div>
                                                    <div class="panel-element-body row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-sm-4">Iso_date:</div>
                                                            <div class="col-xs-12 col-sm-8">{{ $period['target'][0]['value'] }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-heading">Comment</div>
                                                    <div class="panel-body row">
                                                        @foreach($period['target'][0]['comment'] as $comment)
                                                            @foreach($comment['narrative'] as $narrative)
                                                                <div class="panel-element-body row">
                                                                    <div class="col-xs-12 col-md-12">
                                                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Actual</div>
                                                    <div class="panel-element-body row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-sm-4">Iso_date:</div>
                                                            <div class="col-xs-12 col-sm-8">{{$period['actual'][0]['value']}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-heading">Comment</div>
                                                    <div class="panel-body row">
                                                        @foreach($period['actual'][0]['comment'] as $comment)
                                                            @foreach($comment['narrative'] as $narrative)
                                                                <div class="panel-element-body row">
                                                                    <div class="col-xs-12 col-md-12">
                                                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>

                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
