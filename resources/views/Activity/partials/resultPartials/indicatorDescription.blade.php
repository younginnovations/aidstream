<div class="panel panel-default">
    <div class="panel-heading">
        <div class="activity-element-title">Description</div>
    </div>
    <div class="panel-sub-body row">
        @foreach($indicator['description'] as $description)
            @foreach($description['narrative'] as $narrative)
                <div class="panel-element-body row">
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">Text:</div>
                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language'])}}</div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>