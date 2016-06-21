@extends('Activity.activityBaseTemplate')

@section('title', 'Activity Results - ' . $activityData->IdentifierTitle)

@inject('getCode', 'App\Helpers\GetCodeName')

@section('activity-content')
    <div class="element-panel-heading">
        <div>
            <span>@lang('activityView.results')</span>
            <div class="element-panel-heading-info">
                <span>{{$activityData->IdentifierTitle}}</span>
            </div>
            <div class="panel-action-btn">
                <a href="{{route('activity.show',$id)}}" class="btn btn-primary">View Activity</a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
        <div class="activity-element-wrapper">
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.title')</div>
                <div class="activity-element-info">
                    {!! getFirstNarrative($result['result']['title'][0]) !!}
                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($result['result']['title'][0]['narrative'])])
                </div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.description')</div>
                <div class="activity-element-info">
                    {!! getFirstNarrative($result['result']['description'][0]) !!}
                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($result['result']['description'][0]['narrative'])])
                </div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.aggregation_status')</div>
                @if($result['result']['aggregation_status'] == 1)
                    <div class="activity-element-info">True</div>
                @else
                    <div class="activity-element-info">False</div>
                @endif
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.indicators')</div>
                @foreach($result['result']['indicator'] as $indicator)
                    <div class="indicator-info">
                        <div class="indicator-label">
                            <strong>{!! getFirstNarrative($indicator['title'][0]) !!}</strong>
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($indicator['title'][0]['narrative'])])
                        </div>
                        <div class="expanded show-expanded">
                            @if(session('version') != 'V201')
                                <div class="element-info">
                                    <div class="activity-element-label">@lang('activityView.measure')</div>
                                    <div class="activity-element-info">{!! $getCode->getCodeNameOnly('IndicatorMeasure',$indicator['measure']) !!}</div>
                                </div>
                                <div class="element-info">
                                    <div class="activity-element-label">@lang('activityView.ascending')</div>
                                    @if($indicator['ascending'] == 1)
                                        <div class="activity-element-info">Yes</div>
                                    @elseif($indicator['ascending'] == 0)
                                        <div class="activity-element-info">No</div>
                                    @else
                                        <div class="activity-element-info"><em>Not Available</em></div>
                                    @endif
                                </div>
                                <div class="activity-info">
                                    <div class="activity-element-label">@lang('activityView.description')</div>
                                    <div class="activity-element-info">
                                        {!! getFirstNarrative(getVal($indicator, ['description',0])) !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($indicator,['description',0,'narrative']))])
                                    </div>
                                </div>
                                <div class="element-info">
                                    <div class="activity-element-label">@lang('activityView.indicator_reference')</div>
                                    <div class="activity-element-info">
                                        @if(array_key_exists('reference' , $indicator))
                                            @foreach($indicator['reference'] as $reference)
                                                <li>{!! getIndicatorReference($reference) !!}</li>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="element-info baseline">
                                <div class="activity-element-label">@lang('activityView.baseline_value')</div>
                                <div class="activity-element-info">{!! getResultsBaseLine($indicator['measure'] , $indicator['baseline'][0]) !!}
                                    {!! getFirstNarrative($indicator['baseline'][0]['comment'][0]) !!}
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="period-label">
                                    <span>Period</span>
                                    <span> Target Value </span>
                                    <span> Actual Value </span>
                                </div>
                                @foreach(getIndicatorPeriod($indicator['measure'] , $indicator['period']) as $period)
                                    <div class="period-value">
                                        <span>{!!$period['period'] !!}</span>
                                        <span><a href="#" class="show-more-value">{!!  $period['target_value'] !!}
                                                @include('Activity.partials.resultPartials.target' , ['type' => 'target'])</a></span>
                                        <span><a href="#" class="show-more-value"> {!!  checkIfEmpty($period['actual_value'])  !!}
                                                @include('Activity.partials.resultPartials.target' , ['type' => 'actual'])</a></span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
