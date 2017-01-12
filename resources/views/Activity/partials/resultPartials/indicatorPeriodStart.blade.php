<div class="panel panel-default">
    <div class="panel-heading">
        <div class="activity-element-title">@lang('elementForm.period_start')</div>
    </div>
    <div class="panel-sub-body row">
        <div class="col-xs-12 col-md-12">
            <div class="col-xs-12 col-sm-4">@lang('elementForm.iso_date'):</div>
            <div class="col-xs-12 col-sm-8">{{ formatDate($period['period_start'][0]['date']) }}</div>
        </div>
    </div>
</div>