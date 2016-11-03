@if(!emptyOrHasEmptyTemplate($results))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.results') @if(array_key_exists('Results',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach(groupResultElements($results) as $key => $results)
            <div class="activity-element-list">
                <div class="activity-element-label">{{ $key }}</div>
                <div class="activity-element-info">
                    @foreach($results as $result)
                        <li>
                            {!! getFirstNarrative($result['title'][0]) !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($result['title'][0]['narrative'])])
                        </li>
                        <div class="toggle-btn">
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.description')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative($result['description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($result['description'][0]['narrative'])])
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.aggregation_status')</div>
                                @if($result['aggregation_status'] == 1)
                                    <div class="activity-element-info">True</div>
                                @else
                                    <div class="activity-element-info">False</div>
                                @endif
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.indicators')</div>
                                @foreach($result['indicator'] as $indicator)
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
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.result.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'result'])}}" class="delete pull-right">remove</a>
    </div>
@endif
