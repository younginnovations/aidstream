<div class="panel panel-default">
    <div class="panel-heading">
        <div class="activity-element-title">Title</div>
    </div>
    <div class="panel-element-body row">
        @foreach($result['result']['title'] as $title)
            @foreach($title['narrative'] as $narrative)
                <div class="panel-body panel-element-body row">
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">Text:</div>
                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language'])}}</div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>