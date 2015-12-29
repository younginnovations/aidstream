@extends('app')
@section('content')
    @inject('code', 'App\Helpers\GetCodeName')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-content-heading panel-title-heading">Transaction of <span>{{$activity->IdentifierTitle}}</span></div>
                    <strong><h3>Element Detail</h3></strong>
                    <div class="panel-body">
                        <div>Reference: {{$transactionDetail['reference']}}</div>
                        <div>Humanitarian: {{ isset($transaction['humanitarian']) && $transaction['humanitarian'] == 1 ? 'True' : 'False' }}</div>
                        <strong>Transaction Type</strong>
                        <div>Code: {{$code->getActivityCodeName('TransactionType', $transactionDetail['transaction_type'][0]['transaction_type_code'])}}</div>
                        <strong>Provider Organization</strong>
                        {{--*/ $providerOrg = $transactionDetail['provider_organization'][0] /*--}}
                        <div>Ref: {{$providerOrg['organization_identifier_code']}}</div>
                        <div>Provider_activity_id: {{$providerOrg['provider_activity_id']}}</div>
                        <div>Narrative text: {{$providerOrg['narrative'][0]['narrative']}}</div>
                        <strong>Value</strong>
                        {{--*/ $value = $transactionDetail['value'][0] /*--}}
                        <div>Amount: {{$value['amount'] }}</div>
                        <div>Value date: {{$value['date'] }}</div>
                        <div>Currency: {{$code->getActivityCodeName('Currency', $value['currency'])}}</div>
                        <strong>Description</strong>
                        <div>Narrative text: {{$transactionDetail['description'][0]['narrative'][0]['narrative']}}</div>
                        <strong>Transaction Date</strong>
                        <div>Date: {{$transactionDetail['transaction_date'][0]['date']}}</div>
                        <strong>Receiver Organization</strong>
                        {{--*/ $receiverOrg = $transactionDetail['receiver_organization'][0] /*--}}
                        <div>Ref: {{$receiverOrg['organization_identifier_code']}}</div>
                        <div>Provider_activity_id: {{$receiverOrg['receiver_activity_id']}}</div>
                        <div>Narrative text: {{$receiverOrg['narrative'][0]['narrative']}}</div>
                        <strong>Disbursement Channel</strong>
                        <div>Disbursement Channel Code: {{$code->getActivityCodeName('DisbursementChannel', $transactionDetail['disbursement_channel'][0]['disbursement_channel_code'])}}</div>
                        <strong>Sector</strong>
                        {{--*/ $sector = $transactionDetail['sector'][0] /*--}}
                        <div>Sector Code: {{$code->getActivityCodeName('Sector', $sector['sector_code'])}}</div>
                        <div>Sector Vocabulary: {{$code->getActivityCodeName('SectorVocabulary', $sector['sector_vocabulary'])}}</div>
                        <div>Narrative text: {{$sector['narrative'][0]['narrative']}}</div>
                        <strong>Recipient Country</strong>
                        {{--*/ $recipientCountry = $transactionDetail['recipient_country'][0] /*--}}
                        <div>Recipient Country Code: {{$code->getOrganizationCodeName('Country', $recipientCountry['country_code'])}}</div>
                        <div>Narrative text: {{$recipientCountry['narrative'][0]['narrative']}}</div>
                        <strong>Recipient Region</strong>
                        {{--*/ $recipientRegion = $transactionDetail['recipient_region'][0] /*--}}
                        <div>Recipient Region Code: {{$code->getActivityCodeName('Region', $recipientRegion['region_code'])}}</div>
                        <div>Recipient Region Vocabulary: {{$code->getActivityCodeName('RegionVocabulary', $recipientRegion['vocabulary'])}}</div>
                        <div>Narrative text: {{$recipientRegion['narrative'][0]['narrative']}}</div>
                        <strong>Flow Type</strong>
                        <div>Flow Type: {{$code->getActivityCodeName('FlowType', $transactionDetail['flow_type'][0]['flow_type'])}}</div>
                        <strong>Finance Type</strong>
                        <div>Finance Type: {{$code->getActivityCodeName('FinanceType', $transactionDetail['finance_type'][0]['finance_type'])}}</div>
                        <strong>Aid Type</strong>
                        <div>Aid Type: {{$code->getActivityCodeName('AidType', $transactionDetail['aid_type'][0]['aid_type'])}}</div>
                        <strong>Tied Status</strong>
                        <div>Tied Status: {{$code->getActivityCodeName('TiedStatus', $transactionDetail['tied_status'][0]['tied_status_code'])}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
