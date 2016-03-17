@extends('app')

@section('title', 'Activity Results - ' . $activityData->IdentifierTitle)

@inject('getCode', 'App\Helpers\GetCodeName')

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper result-show">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>Result</span>
                        <div class="element-panel-heading-info">
                            <span>{{$activityData->IdentifierTitle}}</span>
                        </div>
                        <div class="pull-right panel-action-btn">
                            <a href="{{route('activity.show',$id)}}" class="btn btn-primary">View Activity</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default panel-element-detail element-show">
                        <div class="pull-right"><a href="#" class="edit-value"></a></div>
                        <div class="panel-body">
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
                            <div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
                                @include('Activity.partials.resultPartials.title')
                                @include('Activity.partials.resultPartials.description')


                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="activity-element-title">Indicator</div>
                                    </div>
                                    <div class="panel-drop-body">
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
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection
