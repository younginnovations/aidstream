@if(!empty($transactions))
    <div class="panel panel-default">
        <div class="panel-heading">Transactions
            <a href="{{route('activity.transaction.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($transactions as $transaction)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{$getCode->getActivityCodeName('TransactionType', $transaction['transaction']['transaction_type'][0]['transaction_type_code']) .' ; '. $transaction['transaction']['value'][0]['amount'] . ' ; ' . $transaction['transaction']['value'][0]['date']}}
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Ref:</div>
                                <div class="col-xs-12 col-sm-8">{{$transaction['transaction']['reference']}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Transaction Type</div>
                        <div class="panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Code:</div>
                                <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('TransactionType', $transaction['transaction']['transaction_type'][0]['transaction_type_code'])}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Transaction Date</div>
                        <div class="panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Date:</div>
                                <div class="col-xs-12 col-sm-8">{{ formatDate($transaction['transaction']['transaction_date'][0]['date']) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Value</div>
                        <div class="panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Amount:</div>
                                <div class="col-xs-12 col-sm-8">{{$transaction['transaction']['value'][0]['amount']}}</div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Date:</div>
                                <div class="col-xs-12 col-sm-8">{{ formatDate($transaction['transaction']['value'][0]['date']) }}</div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Currency:</div>
                                <div class="col-xs-12 col-sm-8">{{$getCode->getOrganizationCodeName('Currency', $transaction['transaction']['value'][0]['currency'])}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Description</div>
                        <div class="panel-body row">
                            @foreach($transaction['transaction']['description'] as $description)
                                @foreach($description['narrative'] as $narrative)
                                    <div class="panel-element-body row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-sm-4">Text:</div>
                                            <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Provider Organization</div>
                        <div class="panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Ref:</div>
                                <div class="col-xs-12 col-sm-8">{{$transaction['transaction']['provider_organization'][0]['organization_identifier_code']}}</div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Provider_activity_id:</div>
                                <div class="col-xs-12 col-sm-8">{{$transaction['transaction']['provider_organization'][0]['provider_activity_id']}}</div>
                            </div>
                            @foreach($transaction['transaction']['provider_organization'] as $narrative)
                                @foreach($narrative['narrative'] as $narrative)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Receiver Organization</div>
                        <div class="panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Ref:</div>
                                <div class="col-xs-12 col-sm-8">{{$transaction['transaction']['receiver_organization'][0]['organization_identifier_code']}}</div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Provider_activity_id:</div>
                                <div class="col-xs-12 col-sm-8">{{$transaction['transaction']['receiver_organization'][0]['receiver_activity_id']}}</div>
                            </div>
                            @foreach($transaction['transaction']['receiver_organization'] as $narrative)
                                @foreach($narrative['narrative'] as $narrative)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Sector</div>
                        <div class="panel-element-body row">
                            {{--*/
                                $vocabulary = $transaction['transaction']['sector'][0]['sector_vocabulary'];
                                $vocabularyValue = $getCode->getActivityCodeName('SectorVocabulary', $vocabulary);
                                if($vocabulary == null){
                                    $vocabularyValue = '';
                                    $sectorValue = '';
                                }elseif ($vocabulary == 1) {
                                    $sectorValue = $getCode->getActivityCodeName('Sector', $transaction['transaction']['sector'][0]['sector_code']);
                                } elseif ($vocabulary == 2) {
                                    $sectorValue = $getCode->getActivityCodeName('SectorCategory', $transaction['transaction']['sector'][0]['sector_category_code']);
                                } else {
                                    $sectorValue = $transaction['transaction']['sector'][0]['sector_text'];
                                }
                            /*--}}
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Vocabulary:</div>
                                <div class="col-xs-12 col-sm-8">{{ $vocabularyValue }}</div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Code:</div>
                                <div class="col-xs-12 col-sm-8">{{ $sectorValue }}</div>
                            </div>
                            @foreach($transaction['transaction']['sector'] as $narrative)
                                @foreach($narrative['narrative'] as $narrative)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
