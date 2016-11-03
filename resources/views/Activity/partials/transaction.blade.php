@if(!emptyOrHasEmptyTemplate($transactions))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.transaction') @if(array_key_exists('Transaction',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach(groupTransactionElements($transactions) as $key => $groupedTransactions)
            <div class="activity-element-list">
                <div class="activity-element-label">{{$key}}</div>
                <div class="activity-element-info">
                    @foreach($groupedTransactions as $transaction)
                        <li>{!! getCurrencyValueDate($transaction['value'][0] , "transaction") !!}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.transaction_reference')</div>
                                <div class="activity-element-info">{!! checkIfEmpty($transaction['reference']) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.description')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative($transaction['description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['description'][0]['narrative'])])
                                </div>
                            </div>
                            @if(session('version') != 'V201')
                                @if(array_key_exists('humanitarian' , $transaction))
                                    <div class="element-info">
                                        <div class="activity-element-label">@lang('activityView.humanitarian')</div>
                                        @if($transaction['humanitarian'] == "")
                                            <div class="activity-element-info"><em>Not Available</em></div>
                                        @elseif($transaction['humanitarian'] == 1)
                                            <div class="activity-element-info">Yes</div>
                                        @elseif($transaction['humanitarian'] == 0)
                                            <div class="activity-element-info">No</div>
                                        @endif
                                    </div>
                                @endif
                            @endif
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.transaction_type')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('TransactionType' , $transaction['transaction_type'][0]['transaction_type_code'] , -4) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.transaction_date')</div>
                                <div class="activity-element-info">{{ formatDate($transaction['transaction_date'][0]['date']) }}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.provider_organization')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative($transaction['provider_organization'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['provider_organization'][0]['narrative'])])
                                    {!! getTransactionProviderDetails($transaction['provider_organization'][0] , 'provider') !!}
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.receiver_organization')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative($transaction['receiver_organization'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['receiver_organization'][0]['narrative'])])
                                    {!! getTransactionProviderDetails($transaction['receiver_organization'][0] , 'receiver') !!}
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.disbursement_channel')</div>
                                <div class="activity-element-info">{!! checkIfEmpty($getCode->getCodeNameOnly('DisbursementChannel' , getVal($transaction, ['disbursement_channel',0,'disbursement_channel_code']))) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.sector')</div>
                                <div class="activity-element-info">
                                    {!! getSectorInformation(getVal($transaction,['sector',0],[] ), "") !!}
                                    {!! getTransactionSectorDetails(getVal($transaction,['sector',0],[])) !!} <br>
                                    {!! getFirstNarrative(getVal($transaction,['sector',0],[])) !!}
                                </div>
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transaction,['sector',0,'narrative'],[]))])
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.recipient_country')</div>
                                <div class="activity-element-info">
                                    {!! getCountryNameWithCode(getVal($transaction, ['recipient_country',0,'country_code'])) !!}
                                    <br>
                                    @if(!empty($transaction['recipient_country'][0]['narrative'][0]['narrative']))
                                        {!! getFirstNarrative($transaction['recipient_country'][0]) !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['recipient_country'][0]['narrative'])])
                                    @endif
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.recipient_region')</div>
                                <div class="activity-element-info">
                                    {!! getCodeNameWithCodeValue('Region' , getVal($transaction,['recipient_region',0,'region_code']) , -5) !!}
                                    <br>
                                    {!! getRecipientRegionDetails(getVal($transaction, ['recipient_region', 0],[])) !!} <br> <br>
                                    @if(!empty($transaction['recipient_region'][0]['narrative'][0]['narrative']))
                                        {!! getFirstNarrative($transaction['recipient_region'][0]) !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['recipient_region'][0]['narrative'])])
                                    @endif
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.flow_type')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('FlowType' , getVal($transaction, ['flow_type',0,'flow_type']) , -4) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.finance_type')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('FinanceType' , getVal($transaction, ['finance_type',0,'finance_type']) , -5) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.aid_type')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('AidType' , getVal($transaction,['aid_type',0,'aid_type'] ), -5) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.tied_status')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('TiedStatus' , getVal($transaction, ['tied_status', 0, 'tied_status_code']) , -4) !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.transaction.index', $id)}}" class="edit-element">edit</a>
    </div>
@endif
