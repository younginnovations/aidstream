@if(!empty($titles))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="activity-element-title">
                Title
            </div>
            <a href="{{route('activity.title.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($titles as $title)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="activity-element-title">
                            {{$title['narrative'] . hideEmptyArray('Organization', 'Language', $title['language']) }}
                        </div>
                    </div>
                    <div class="panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Narrative Text:</div>
                            <div class="col-xs-12 col-sm-8">{{$title['narrative'] . hideEmptyArray('Organization', 'Language', $title['language']) }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
