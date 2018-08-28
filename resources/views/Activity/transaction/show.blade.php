@extends('Activity.activityBaseTemplate')

@section('title', trans('title.transactions').'- ' . $activity->IdentifierTitle)

@section('activity-content')
    @inject('getCode', 'App\Helpers\GetCodeName')
    <div class="element-panel-heading">
        <div>
            <span>@lang('element.transaction')</span>
            <div class="element-panel-heading-info"><span>{{$activity->IdentifierTitle}}</span></div>
            <div class="panel-action-btn">
                <a href="{{ route('activity.show', $id) }}" class="btn btn-primary btn-view-it">@lang('global.view_activity')</a>
                <a href="{{route('activity.transaction.index', $id) }}" class="btn btn-primary btn-view-it">@lang('global.view_transaction')</a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
        <div class="activity-element-wrapper">
        <a href="{{route('activity.transaction.edit',[$id, $transactionId]) }}" class="edit" >@lang('global.edit')</a>
        <form method="POST" id="test-form" action="{{ route('activity.transaction.destroy', [$id, $transactionId] )}}">
           {{ csrf_field() }}
           {{ method_field('DELETE') }}
        <input type="submit" id="testBtn">
        </form>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.value')</div>
                <div class="activity-element-info">{!! getCurrencyValueDate(getVal($transactionDetail,['value',0]), "transaction") !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.transaction_type')</div>
                <div class="activity-element-info">{!! getCodeNameWithCodeValue('TransactionType' , getVal($transactionDetail, ['transaction_type',0,'transaction_type_code']) , -4) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.transaction_reference')</div>
                <div class="activity-element-info">{!! checkIfEmpty($transactionDetail['reference']) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.transaction_date')</div>
                <div class="activity-element-info">{{ formatDate($transactionDetail['transaction_date'][0]['date']) }}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.description')</div>
                <div class="activity-element-info">
                    {!! getFirstNarrative($transactionDetail['description'][0])!!}
                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transactionDetail['description'][0]['narrative'])])
                </div>
            </div>
            @if(session('version') != 'V201')
                @if(array_key_exists('humanitarian' , $transactionDetail))
                    <div class="activity-element-list">
                        <div class="activity-element-label">@lang('elementForm.humanitarian')</div>
                        @if($transactionDetail['humanitarian'] == "")
                            <div class="activity-element-info"><em>@lang('global.not_available')</em></div>
                        @elseif($transactionDetail['humanitarian'] == 1)
                            <div class="activity-element-info">@lang('elementForm.yes')</div>
                        @elseif($transactionDetail['humanitarian'] == 0)
                            <div class="activity-element-info">@lang('elementForm.no')</div>
                        @endif
                    </div>
                @endif
            @endif
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.provider_organisation')</div>
                <div class="activity-element-info">
                    {!! getFirstNarrative(getVal($transactionDetail, ['provider_organization', 0], []), trans('global.no_name_available')) !!}
                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transactionDetail,['provider_organization',0,'narrative']))])
                    {!! getTransactionProviderDetails($transactionDetail['provider_organization'][0] , 'provider') !!}
                </div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.receiver_organisation')</div>
                <div class="activity-element-info">
                    {!! getFirstNarrative(getVal($transactionDetail, ['receiver_organization', 0], []), trans('global.no_name_available')) !!}
                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transactionDetail,['receiver_organization',0,'narrative']))])
                    {!! getTransactionProviderDetails($transactionDetail['receiver_organization'][0] , 'receiver') !!}
                </div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.disbursement_channel')</div>
                <div class="activity-element-info">{!! checkIfEmpty($getCode->getCodeNameOnly('DisbursementChannel' , getVal($transactionDetail, ['disbursement_channel',0,'disbursement_channel_code']))) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.sector')</div>
                <div class="activity-element-info">
                    @foreach (array_get($transactionDetail, 'sector', []) as $sector)
                        <li>{!! getSectorInformation($sector, '') !!}</li>
                        <ul>{!! getTransactionSectorDetails($sector) !!}</ul><br>
                        <ul>{!! getFirstNarrative($sector) !!}</ul>
                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(array_get($sector, 'narrative', []))])
                        <br>
                    @endforeach
                </div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.recipient_country')</div>
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
                <div class="activity-element-label">@lang('elementForm.recipient_region')</div>
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
                <div class="activity-element-label">@lang('elementForm.flow_type')</div>
                <div class="activity-element-info">{!! getCodeNameWithCodeValue('FlowType' , getVal($transactionDetail, ['flow_type',0,'flow_type']) , -4) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.finance_type')</div>
                <div class="activity-element-info">{!! getCodeNameWithCodeValue('FinanceType' , getVal($transactionDetail, ['finance_type',0,'finance_type']) , -5) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.aid_type')</div>
                <div class="activity-element-info">{!! getCodeNameWithCodeValue('AidType' , getVal($transactionDetail,['aid_type',0,'aid_type'] ), -5) !!}</div>
            </div>
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('elementForm.tied_status_code')</div>
                <div class="activity-element-info">{!! getCodeNameWithCodeValue('TiedStatus' , getVal($transactionDetail, ['tied_status', 0, 'tied_status_code']) , -4) !!}</div>
            </div>
        </div>
    </div>
@stop
