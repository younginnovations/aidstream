@if(!empty($descriptions))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="activity-element-title">
                Description
            </div>
            <a href="{{route('activity.description.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($descriptions as $description)
                <div class="panel panel-default">
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Type:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('DescriptionType', $description['type'])}}</div>
                        </div>
                        @foreach($description['narrative'] as $narrative)
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Narrative Text:</div>
                                <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' [' . $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
