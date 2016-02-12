@if(!empty($conditions))
    <div class="panel panel-default">
        <div class="panel-heading">Conditions
            <a href="{{route('activity.condition.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            <div class="panel panel-default">
                <div class="panel-element-body">
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">Attached: </div>
                        <div class="col-xs-12 col-sm-8"> {{($conditions['condition_attached'] == "1") ? 'Yes' : 'No' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Description: </div>
                    <div class="panel-element-body row">
                        @foreach($conditions['condition'] as $data)
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Type</div>
                                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('ConditionType', $data['condition_type'])}}</div>
                            </div>
                            @foreach($data['narrative'] as $narrative)

                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Text: </div>
                                    <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                </div>

                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
