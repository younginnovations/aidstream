@if(!emptyOrHasEmptyTemplate($results))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.results') @if(array_key_exists('Results',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach(groupResultElements($results) as $key => $results)
            <div class="activity-element-list">
                <div class="activity-element-label">{{ $key }}</div>
                <div class="activity-element-info">
                    @foreach($results as $result)
                        <li>
                            {!! getFirstNarrative(getVal($result, ['title', 0], [])) !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($result, ['title', 0, 'narrative'], []))])
                        </li>
                        <div class="toggle-btn">
                            <span class="show-more-info">@lang('global.show_more_info')</span>
                            <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.description')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative(getVal($result, ['description', 0], [])) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($result, ['description', 0, 'narrative'], []))])
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.aggregation_status')</div>
                                @if(getVal($result, ['aggregation_status']) == 1)
                                    <div class="activity-element-info">@lang('global.true')</div>
                                @else
                                    <div class="activity-element-info">@lang('global.false')</div>
                                @endif
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.indicators')</div>
                                @foreach($result['indicator'] as $indicator)
                                    <div class="indicator-info">
                                        <div class="indicator-label">
                                            <strong>{!! getFirstNarrative(getVal($indicator, ['title', 0], [])) !!}</strong>
                                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($indicator, ['title', 0, 'narrative'], []))])
                                        </div>
                                        <div class="expanded show-expanded">
                                            @if(session('version') != 'V201')
                                                <div class="element-info">
                                                    <div class="activity-element-label">@lang('elementForm.measure')</div>
                                                    <div class="activity-element-info">{!! $getCode->getCodeNameOnly('IndicatorMeasure', getVal($indicator, ['measure'])) !!}</div>
                                                </div>
                                                <div class="element-info">
                                                    <div class="activity-element-label">@lang('elementForm.ascending')</div>
                                                    @if(getVal($indicator, ['ascending']) == 1)
                                                        <div class="activity-element-info">@lang('elementForm.yes')</div>
                                                    @elseif(getVal($indicator, ['ascending']) == 0)
                                                        <div class="activity-element-info">@lang('elementForm.no')</div>
                                                    @else
                                                        <div class="activity-element-info"><em>@lang('global.not_available')</em></div>
                                                    @endif
                                                </div>
                                                <div class="activity-info">
                                                    <div class="activity-element-label">@lang('elementForm.description')</div>
                                                    <div class="activity-element-info">
                                                        {!! getFirstNarrative(getVal($indicator, ['description',0])) !!}
                                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($indicator,['description',0,'narrative']))])
                                                    </div>
                                                </div>
                                                <div class="element-info">
                                                    <div class="activity-element-label">@lang('elementForm.indicator_reference')</div>
                                                    <div class="activity-element-info">
                                                        @foreach(getVal($indicator, ['reference'], []) as $reference)
                                                            <li>{!! getIndicatorReference($reference) !!}</li>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="element-info baseline">
                                                <div class="activity-element-label">@lang('elementForm.baseline_value')</div>
                                                <div class="activity-element-info">{!! getResultsBaseLine(getVal($indicator, ['measure']) , getVal($indicator, ['baseline', 0], [])) !!}
                                                    {!! getFirstNarrative(getVal($indicator, ['baseline', 0, 'comment', 0], [])) !!}
                                                </div>
                                            </div>
                                            <div class="element-info">
                                                <div class="period-label">
                                                    <span>@lang('elementForm.period')</span>
                                                    <span>@lang('elementForm.target_value')</span>
                                                    <span>@lang('elementForm.actual_value')</span>
                                                </div>
                                                @foreach(getIndicatorPeriod(getVal($indicator, ['measure']) , getVal($indicator, ['period'], [])) as $period)
                                                    <div class="period-value">
                                                        <span>{!!getVal($period, ['period']) !!}</span>
                                                        <span><a href="#" class="show-more-value">{!!  getVal($period, ['target_value']) !!}
                                                                @include('Activity.partials.resultPartials.target' , ['type' => 'target'])</a></span>
                                                        <span><a href="#" class="show-more-value"> {!!  checkIfEmpty(getVal($period, ['actual_value']))  !!}
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
        <a href="{{route('activity.result.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        <a href="{{route('activity.delete-element', [$id, 'result'])}}" class="delete pull-right">@lang('global.remove')</a>
    </div>
@endif
