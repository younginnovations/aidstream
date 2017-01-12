<div class="panel panel-default">
    <div class="panel-heading">
        <div class="activity-element-title">@lang('elementForm.baseline')</div>
    </div>
    <div class="panel-sub-body row panel-level-3">
        @foreach($indicator['baseline'] as $baseline)
            <div class="panel panel-default">
                <div class="panel-element-body row">
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">@lang('elementForm.year'):</div>
                        <div class="col-xs-12 col-sm-8">{{$baseline['year']}}</div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">@lang('elementForm.value'):</div>
                        <div class="col-xs-12 col-sm-8">{{$baseline['value']}}</div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="activity-element-title">@lang('elementForm.comment')</div>
                </div>
                <div class="panel-sub-body row">
                    @foreach($baseline['comment'] as $comment)
                        @foreach($comment['narrative'] as $narrative)
                            <div class="panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">@lang('elementForm.text'):
                                    </div>
                                    <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language'])}}</div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>