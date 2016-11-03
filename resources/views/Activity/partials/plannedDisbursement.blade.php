@if(!emptyOrHasEmptyTemplate($plannedDisbursements))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.planned_disbursement') @if(array_key_exists('Planned Disbursement',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach( groupBudgetElements($plannedDisbursements , 'planned_disbursement_type') as $key => $disbursements)
            <div class="activity-element-list">
                <div class="activity-element-label">{{ $getCode->getCodeNameOnly('BudgetType' , $key) }}</div>
                <div class="activity-element-info">
                    @foreach($disbursements as $disbursement)
                        <li>{!! getCurrencyValueDate($disbursement['value'][0] , "planned") !!}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.period')</div>
                                <div class="activity-element-info">{!! getBudgetPeriod($disbursement) !!}</div>
                            </div>
                            @if(session('version') != 'V201')
                                <div class="element-info">
                                    <div class="activity-element-label">@lang('activityView.provider_organization')</div>
                                    <div class="activity-element-info">
                                        {!!  getFirstNarrative(getVal($disbursement, ['provider_org',0],[]))  !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($disbursement,['provider_org',0,'narrative'],[]))])
                                        {!! getDisbursementOrganizationDetails($disbursement , 'provider_org') !!}
                                    </div>
                                </div>
                                <div class="element-info">
                                    <div class="activity-element-label">@lang('activityView.receiver_organization')</div>
                                    <div class="activity-element-info">
                                        {!!  getFirstNarrative(getVal($disbursement, ['receiver_org', 0],[]))  !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($disbursement,['receiver_org',0,'narrative'],[]))])
                                        {!! getDisbursementOrganizationDetails($disbursement , 'receiver_org') !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.planned-disbursement.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'planned_disbursement'])}}" class="delete pull-right">remove</a>
    </div>
@endif
