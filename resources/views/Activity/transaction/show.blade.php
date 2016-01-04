@extends('app')
@section('content')
    @inject('code', 'App\Helpers\GetCodeName')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
            <div class="panel-content-heading panel-title-heading">Transaction of <span>{{$activity->IdentifierTitle}}</span></div>
            <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                <div class="panel panel-default panel-element-detail">
                    <div class="panel-body">
                        <div class="panel panel-default">  
                            <div class="panel-heading">Element Detail</div>     
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Reference:</div>
                                    <div class="col-xs-12 col-sm-8">{{$transactionDetail['reference']}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Humanitarian:</div>
                                    <div class="col-xs-12 col-sm-8">{{ isset($transaction['humanitarian']) && $transaction['humanitarian'] == 1 ? 'True' : 'False' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">  
                            <div class="panel-heading">Transaction Type</div>     
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('TransactionType', $transactionDetail['transaction_type'][0]['transaction_type_code'])}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Humanitarian:</div>
                                    <div class="col-xs-12 col-sm-8">{{ isset($transaction['humanitarian']) && $transaction['humanitarian'] == 1 ? 'True' : 'False' }}</div>
                                </div>
                            </div>
                        </div>
                         <div class="panel panel-default">  
                            <div class="panel-heading">Provider Organization</div>     
                            {{--*/ $providerOrg = $transactionDetail['provider_organization'][0] /*--}}
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Ref:</div>
                                    <div class="col-xs-12 col-sm-8">{{$providerOrg['organization_identifier_code']}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Provider_activity_id:</div>
                                    <div class="col-xs-12 col-sm-8">{{$providerOrg['provider_activity_id']}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Narrative text:</div>
                                    <div class="col-xs-12 col-sm-8">{{$providerOrg['narrative'][0]['narrative']}}</div>
                                </div>
                            </div>
                        </div>
                         <div class="panel panel-default">  
                            <div class="panel-heading">Value</div>     
                            {{--*/ $value = $transactionDetail['value'][0] /*--}}
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Amount:</div>
                                    <div class="col-xs-12 col-sm-8">{{$value['amount'] }}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Value date:</div>
                                    <div class="col-xs-12 col-sm-8">{{$value['date'] }}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Currency:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('Currency', $value['currency'])}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">  
                            <div class="panel-heading">Description</div>     
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Narrative text:</div>
                                    <div class="col-xs-12 col-sm-8">{{$transactionDetail['description'][0]['narrative'][0]['narrative']}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">  
                            <div class="panel-heading">Transaction Date</div>     
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Date:</div>
                                    <div class="col-xs-12 col-sm-8">{{$transactionDetail['transaction_date'][0]['date']}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">  
                            <div class="panel-heading">Receiver Organization</div>     
                            {{--*/ $receiverOrg = $transactionDetail['receiver_organization'][0] /*--}}
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Ref:</div>
                                    <div class="col-xs-12 col-sm-8">{{$receiverOrg['organization_identifier_code']}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Provider_activity_id:</div>
                                    <div class="col-xs-12 col-sm-8">{{$receiverOrg['receiver_activity_id']}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Narrative text:</div>
                                    <div class="col-xs-12 col-sm-8">{{$receiverOrg['narrative'][0]['narrative']}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">  
                            <div class="panel-heading">Disbursement Channel</div>     
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Disbursement Channel Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('DisbursementChannel', $transactionDetail['disbursement_channel'][0]['disbursement_channel_code'])}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">  
                            <div class="panel-heading">Sector</div>     
                            {{--*/ $sector = $transactionDetail['sector'][0] /*--}}
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Sector Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('Sector', $sector['sector_code'])}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Sector Vocabulary:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('SectorVocabulary', $sector['sector_vocabulary'])}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Narrative text:</div>
                                    <div class="col-xs-12 col-sm-8">{{$sector['narrative'][0]['narrative']}}</div>
                                </div>
                            </div>
                        </div>
                         <div class="panel panel-default">  
                            <div class="panel-heading">Recipient Country</div>     
                            {{--*/ $recipientCountry = $transactionDetail['recipient_country'][0] /*--}}
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Recipient Country Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getOrganizationCodeName('Country', $recipientCountry['country_code'])}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Sector Vocabulary:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('SectorVocabulary', $sector['sector_vocabulary'])}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Narrative text:</div>
                                    <div class="col-xs-12 col-sm-8">{{$sector['narrative'][0]['narrative']}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">  
                            <div class="panel-heading">Recipient Region</div>     
                            {{--*/ $recipientRegion = $transactionDetail['recipient_region'][0] /*--}}
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Recipient Region Code:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('Region', $recipientRegion['region_code'])}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Recipient Region Vocabulary:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('RegionVocabulary', $recipientRegion['vocabulary'])}}</div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Narrative text:</div>
                                    <div class="col-xs-12 col-sm-8">{{$recipientRegion['narrative'][0]['narrative']}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">  
                            <div class="panel-heading">Flow Type</div>     
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Flow Type:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('FlowType', $transactionDetail['flow_type'][0]['flow_type'])}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">  
                            <div class="panel-heading">Finance Type</div>     
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Finance Type:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('FinanceType', $transactionDetail['finance_type'][0]['finance_type'])}}</div>
                                </div>
                            </div>
                        </div>
                         <div class="panel panel-default">  
                            <div class="panel-heading">Aid Type</div>     
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Aid Type:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('AidType', $transactionDetail['aid_type'][0]['aid_type'])}}</div>
                                </div>
                            </div>
                        </div>
                         <div class="panel panel-default">  
                            <div class="panel-heading">Tied Type</div>     
                            <div class="panel-body panel-element-body">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-4">Finance Type:</div>
                                    <div class="col-xs-12 col-sm-8">{{$code->getActivityCodeName('TiedStatus', $transactionDetail['tied_status'][0]['tied_status_code'])}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@stop
