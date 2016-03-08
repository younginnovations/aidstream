<div class="panel panel-default">
    <div class="panel-heading">
        <div class="activity-element-title">Target</div>
    </div>
    <div class="panel-sub-body row">
        <div class="col-xs-12 col-md-12">
            <div class="col-xs-12 col-sm-4">Iso_date:</div>
            <div class="col-xs-12 col-sm-8">{{ $period['target'][0]['value'] }}</div>
        </div>
        <div class="panel-heading">
            <div class="activity-element-title">Comment</div>
        </div>
        <div class="panel-body row">
            @foreach($period['target'][0]['comment'] as $comment)
                @foreach($comment['narrative'] as $narrative)
                    <div class="panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Text:
                            </div>
                            <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language'])}}</div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

</div>