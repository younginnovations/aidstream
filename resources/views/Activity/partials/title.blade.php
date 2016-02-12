@if(!empty($titles))
    <div class="panel panel-default">
        <div class="panel-heading">Title
            <a href="{{route('activity.title.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($titles as $title)
                <div class="panel panel-default">
                    <div class="panel-heading">{{$title['narrative'] . ' [' . $title['language'] . ']'}}</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Narrative Text: </div>
                            <div class="col-xs-12 col-sm-8">{{$title['narrative'] . ' [' . $title['language'] . ']'}}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
