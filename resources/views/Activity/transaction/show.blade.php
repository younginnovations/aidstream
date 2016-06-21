@extends('Activity.activityBaseTemplate')

@section('title', 'Activity Transaction - ' . $activity->IdentifierTitle)

@section('activity-content')
    @inject('getCode', 'App\Helpers\GetCodeName')
    <div class="element-panel-heading">
        <div>
            <span>Transaction</span>
            <div class="element-panel-heading-info"><span>{{$activity->IdentifierTitle}}</span></div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
        <div class="activity-element-wrapper">
            <div class="activity-element-list">
                <div class="activity-element-label">Value</div>
                <div class="activity-element-info">{!! getCurrencyValueDate(getVal($transactionDetail,['value',0]), "transaction") !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.transaction_type')</div>
                <div class="activity-element-info">{!! getCodeNameWithCodeValue('TransactionType' , getVal($transactionDetail, ['transaction_type',0,'transaction_type_code']) , -4) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.transaction_reference')</div>
                <div class="activity-element-info">{!! checkIfEmpty($transactionDetail['reference']) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.transaction_date')</div>
                <div class="activity-element-info">{{ formatDate($transactionDetail['transaction_date'][0]['date']) }}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.description')</div>
                <div class="activity-element-info">
                    {!! getFirstNarrative($transactionDetail['description'][0])!!}
                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transactionDetail['description'][0]['narrative'])])
                </div>
            </div>
            @if(session('version') != 'V201')
                @if(array_key_exists('humanitarian' , $transactionDetail))
                    <div class="activity-element-list">
                        <div class="activity-element-label">@lang('activityView.humanitarian')</div>
                        @if($transactionDetail['humanitarian'] == "")
                            <div class="activity-element-info"><em>Not Available</em></div>
                        @elseif($transactionDetail['humanitarian'] == 1)
                            <div class="activity-element-info">Yes</div>
                        @elseif($transactionDetail['humanitarian'] == 0)
                            <div class="activity-element-info">No</div>
                        @endif
                    </div>
                @endif
            @endif
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.provider_organization')</div>
                <div class="activity-element-info">
                    {!! getFirstNarrative($transactionDetail['provider_organization'][0]) !!}
                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transactionDetail,['provider_organization',0,'narrative']))])
                    {!! getTransactionProviderDetails($transactionDetail['provider_organization'][0] , 'provider') !!}
                </div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.receiver_organization')</div>
                <div class="activity-element-info">
                    {!! getFirstNarrative($transactionDetail['receiver_organization'][0]) !!}
                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transactionDetail,['receiver_organization',0,'narrative']))])
                    {!! getTransactionProviderDetails($transactionDetail['receiver_organization'][0] , 'receiver') !!}
                </div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.disbursement_channel')</div>
                <div class="activity-element-info">{!! checkIfEmpty($getCode->getCodeNameOnly('DisbursementChannel' , getVal($transactionDetail, ['disbursement_channel',0,'disbursement_channel_code']))) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.sector')</div>
                <div class="activity-element-info">
                    {!! getSectorInformation(getVal($transactionDetail,['sector',0],[] ), "") !!}
                    {!! getTransactionSectorDetails(getVal($transactionDetail,['sector',0],[])) !!} <br>
                    {!! getFirstNarrative(getVal($transactionDetail,['sector',0],[])) !!}
                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transactionDetail,['sector',0,'narrative'],[]))])
                </div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.recipient_country')</div>
                <div class="activity-element-info">
                    {!! getCountryNameWithCode(getVal($transactionDetail, ['recipient_country',0,'country_code'])) !!}
                    <br>
                    @if(!empty($transactionDetail['recipient_country'][0]['narrative'][0]['narrative']))
                        {!! getFirstNarrative($transactionDetail['recipient_country'][0]) !!}
                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transactionDetail['recipient_country'][0]['narrative'])])
                    @endif
                </div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.recipient_region')</div>
                <div class="activity-element-info">
                    {!! getCodeNameWithCodeValue('Region' , getVal($transactionDetail,['recipient_region',0,'region_code']) , -5) !!}
                    <br>
                    {!! getRecipientRegionDetails(getVal($transactionDetail, ['recipient_region', 0],[])) !!} <br> <br>
                    @if(!empty($transactionDetail['recipient_region'][0]['narrative'][0]['narrative']))
                        {!! getFirstNarrative($transactionDetail['recipient_region'][0]) !!}
                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transactionDetail['recipient_region'][0]['narrative'])])
                    @endif
                </div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.flow_type')</div>
                <div class="activity-element-info">{!! getCodeNameWithCodeValue('FlowType' , getVal($transactionDetail, ['flow_type',0,'flow_type']) , -4) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.finance_type')</div>
                <div class="activity-element-info">{!! getCodeNameWithCodeValue('FinanceType' , getVal($transactionDetail, ['finance_type',0,'finance_type']) , -5) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.aid_type')</div>
                <div class="activity-element-info">{!! getCodeNameWithCodeValue('AidType' , getVal($transactionDetail,['aid_type',0,'aid_type'] ), -5) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.tied_status')</div>
                <div class="activity-element-info">{!! getCodeNameWithCodeValue('TiedStatus' , getVal($transactionDetail, ['tied_status', 0, 'tied_status_code']) , -4) !!}</div>
            </div>
        </div>
    </div>
@stop
