@if(!empty($otherIdentifiers))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">
                Other Identifier
            </div>
            <a href="{{route('activity.other-identifier.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($otherIdentifiers as $other_identifier)
                <div class="panel-heading">
                    <div class="activity-element-title">{{$other_identifier['reference']}}</div>
                </div>
                <div class="panel-body row">
                    <div class="panel-element-body">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Reference:</div>
                            <div class="col-xs-12 col-sm-8">{{$other_identifier['reference']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Type:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('OtherIdentifierType', $other_identifier['type'])}}</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Owner Org</div>
                            <div class="panel-body panel-element-body row">
                                @foreach($other_identifier['owner_org'] as $owner_org)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Owner Org:</div>
                                        <div class="col-xs-12 col-sm-8">{{$owner_org['reference']}}</div>
                                    </div>
                                    @foreach($owner_org['narrative'] as $narrative)
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-sm-4">Text:</div>
                                            <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language'])}}</div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
