@if ($project->participating_organization)
    <div class="activity-element-wrapper">
        @foreach ($project->participating_organization as $participatingOrganization)
            @if(getVal($participatingOrganization, ['narrative', 0, 'narrative']) && getVal($participatingOrganization, ['organization_role']) == "1")
                <div class="activity-element-list">
                    <div class="activity-element-label">
                        Funding Organization
                    </div>
                    <div class="activity-element-info">
                        <li>
                            {{ getVal($participatingOrganization, ['narrative', 0, 'narrative']) }}
                            , {{$getCode->getCodeListName('Activity','OrganisationType', getVal($participatingOrganization, ['organization_type']))}}
                        </li>
                    </div>
                </div>
            @endif

            @if(getVal($participatingOrganization, ['narrative', 0, 'narrative']) && getVal($participatingOrganization, ['organization_role']) == 4)
                <div class="activity-element-list">
                    <div class="activity-element-label">
                        Implementing Organization
                    </div>
                    <div class="activity-element-info">
                        <li>
                            {{ getVal($participatingOrganization, ['narrative', 0, 'narrative']) }}
                            , {{$getCode->getCodeListName('Activity','OrganisationType', getVal($participatingOrganization, ['organization_type']))}}
                        </li>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif
