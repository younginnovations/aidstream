@if(!emptyOrHasEmptyTemplate($participatingOrganizations))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">
                Participating Organization
            </div>
            <a href="{{route('activity.participating-organization.index', $id)}}" class="edit-element">edit</a>
            <a href="{{route('activity.delete-element', [$id, 'participating_organization'])}}" class="delete pull-right">remove</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($participatingOrganizations as $participatingOrganization)
                <div class="panel-heading">
                    <div class="activity-element-title">{{$getCode->getActivityCodeName('OrganisationRole', $participatingOrganization['organization_role'])}}
                        ; {{$participatingOrganization['narrative'][0]['narrative'] . hideEmptyArray('Organization', 'Language', $participatingOrganization['narrative'][0]['language'])}}</div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-body panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Ref:</div>
                                <div class="col-xs-12 col-sm-8">{{$participatingOrganization['identifier']}}</div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Type:</div>
                                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('OrganisationType', $participatingOrganization['organization_type'])}}</div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Role:</div>
                                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('OrganisationRole', $participatingOrganization['organization_role'])}}</div>
                            </div>
                            @foreach($participatingOrganization['narrative'] as $narrative)
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Organization Name:</div>
                                    <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language']) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
