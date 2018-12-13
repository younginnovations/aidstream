<div class="panel panel-default panel-element-detail element-show">

    @foreach (getVal($activity->toArray(), ['description'], []) as $description)
        @if(getVal($description, ['type']) == 1)
            <div class="activity__detail">
                <div class="activity__element__list">
                    <h3>
                        @lang('lite/elementForm.general_description')
                    </h3>
                    <div class="activity__element--info">
                        {{$description['narrative'][0]['narrative']}}
                    </div>
                </div>
            </div>
        @endif
        @if(getVal($description, ['type']) == 2)
            <div class="activity__detail">
                <div class="activity__element__list">
                    <h3>
                        @lang('lite/elementForm.objectives')
                    </h3>
                    <div class="activity__element--info">
                        {{$description['narrative'][0]['narrative']}}
                    </div>
                </div>
            </div>
        @endif
        @if(getVal($description, ['type']) == 3)
            <div class="activity__detail">
                <div class="activity__element__list">
                    <h3>
                        @lang('lite/elementForm.target_groups')
                    </h3>
                    <div class="activity__element--info">
                        {{$description['narrative'][0]['narrative']}}
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @if((array) $activity->sector)
        <div class="activity__detail">
            <div class="activity__element__list">
                <h3>
                    @lang('lite/elementForm.sector')
                </h3>
                @foreach($activity->sector as $index => $sector)
                    <div class="activity__element--info">
                        <li>
                            {{ getVal($sector, ['sector_code'], '') }} - {{ $getCode->getCodeNameOnly('Sector', getVal($sector, ['sector_code']),-7)}}
                        </li>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if((array) $activity->recipient_country)
        <div class="activity__detail">
            <div class="activity__element__list">
                <h3>
                    @lang('lite/elementForm.recipient_country')
                </h3>
                    <div class="activity__element--info">
                        <li>Nepal</li>
                    </div>

                @foreach($locationArray as $key => $value)
                <div class="activity__element--info">
                    <li>Municipality - {{$key}}</li>
                    @if($value[0] != null)
                    <li> Ward -<span>
                    {{join(', ', $value)}}
                    </span>
                    </li>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($activity->participating_organization)
        @foreach (groupActivityElements((array) $activity->participating_organization,'organization_role') as $role => $participatingOrganization)
            @if($role == 1)
                <div class="activity__detail">
                    <div class="activity__element__list">
                        <h3>
                            @lang('lite/elementForm.funding_organisation')
                        </h3>
                        @foreach($participatingOrganization as $index => $fundingOrganisation)
                            <div class="activity__element--info">
                                <li>
                                    {{ getVal($fundingOrganisation, ['narrative', 0, 'narrative']) }}
                                    , {{$getCode->getCodeNameOnly('OrganisationType', getVal($fundingOrganisation, ['organization_type']))}}
                                </li>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($role == 4)
                <div class="activity__detail">
                    <div class="activity__element__list">
                        <h3>
                            @lang('lite/elementForm.implementing_organisation')
                        </h3>
                        <div class="activity__element--info">
                        @foreach($participatingOrganization as $index => $implementingOrganization)
                                <li>
                                    {{ getVal($implementingOrganization, ['narrative', 0, 'narrative']) }}
                                    , {{$getCode->getCodeNameOnly('OrganisationType', getVal($implementingOrganization, ['organization_type']))}}
                                </li>
                        @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

    @if(array_key_exists('outcomes_document',$documentLinks))
        <div class="activity__detail">
            <div class="activity__element__list">
                <h3>
                    @lang('lite/elementForm.results_outcomes_documents')
                </h3>
                <div class="activity__element--info">
                    @foreach((array)getVal($documentLinks,['outcomes_document'],[]) as $index => $value)
                        <li>
                            @if(($url = getVal($value,['document_url'])) != "")
                                <a href="{{$url}}">{{getVal($value,['document_title'])}}</a>
                            @else
                                {{getVal($value,['document_title'])}}
                            @endif
                        </li>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if(array_key_exists('annual_report',$documentLinks))
        <div class="activity__detail">
            <div class="activity__element__list">
                <h3>
                    @lang('lite/elementForm.annual_reports')
                </h3>
                <div class="activity__element--info">
                    @foreach((array) getVal($documentLinks,['annual_report'],[]) as $index => $value)
                        <li>
                            @if(($url = getVal($value,['document_url'])) != "")
                                <a href="{{$url}}">{{getVal($value,['document_title'])}}</a>
                            @else
                                {{getVal($value,['document_title'])}}
                            @endif
                        </li>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @include('np.municipalityAdmin.partials.budget')
    @include('np.municipalityAdmin.partials.transaction')
</div>
