<div class="view-period-info hidden">
    <div class="element-info">
        <div class="activity-element-label">@lang('elementForm.location_reference'):</div>
        <div class="activity-element-info">{!! getTargetAdditionalDetails($period[$type] , 'locationRef')!!}</div>
    </div>
    <div class="element-info">
        <div class="activity-element-label">@lang('elementForm.dimension')</div>
        <div class="activity-element-info">
            {!! getTargetAdditionalDetails($period[$type] , 'dimension')!!}
        </div>
    </div>
    <div class="element-info">
        <div class="activity-element-label">@lang('Description')</div>
        <div class="activity-element-info">
            @foreach($period[$type]['comment'][0]['narrative'] as $languages)
                <em>{!! checkIfEmpty($languages['narrative'] , 'Description Not Available').'(language:'.getLanguage($languages['language']).')' !!}</em> <br/>
            @endforeach
        </div>
    </div>
</div>
