@if(!emptyOrHasEmptyTemplate($results))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">
                Results
            </div>
            <a href="{{route('activity.result.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($results as $result)
                <div class="panel-heading">
                    <div class="activity-element-title">{{$getCode->getActivityCodeName('ResultType', $result['result']['type'])}}</div>
                </div>
                <div class="panel-body">
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
                        @include('Activity.partials.resultPartials.title')
                        @include('Activity.partials.resultPartials.description')


                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="activity-element-title">Indicator</div>
                            </div>
                            <div class="panel-level-body">
                                @foreach($result['result']['indicator'] as $indicator)
                                    <div class="panel-heading">
                                        <div class="activity-element-title">{{$getCode->getActivityCodeName('IndicatorMeasure', $indicator['measure'])}}</div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="panel-element-body">
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-sm-4">Measure:</div>
                                                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('IndicatorMeasure', $indicator['measure'])}}</div>
                                            </div>
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-sm-4">Ascending:</div>
                                                <div class="col-xs-12 col-sm-8">{{($indicator['ascending'] == "1") ? 'True' : 'False' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-12 col-lg-12 panel-level-3">
                                            @include('Activity.partials.resultPartials.indicatorTitle')
                                            @include('Activity.partials.resultPartials.indicatorDescription')
                                            @include('Activity.partials.resultPartials.indicatorBaseline')

                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="activity-element-title">Period</div>
                                                </div>
                                                <div class="panel-sub-body panel-level-4">
                                                    @foreach($indicator['period'] as $period)
                                                        @include('Activity.partials.resultPartials.indicatorPeriodStart')
                                                        @include('Activity.partials.resultPartials.indicatorPeriodEnd')
                                                        @include('Activity.partials.resultPartials.indicatorTarget')
                                                        @include('Activity.partials.resultPartials.indicatorActual')
                                                    @endforeach
                                                </div>
                                            </div>
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
