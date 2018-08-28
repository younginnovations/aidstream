@if(!emptyOrHasEmptyTemplate(getVal($activityDataList, ['transaction'], [])))
    @inject('getCode', 'App\Helpers\GetCodeName')
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.transaction') @if(array_key_exists('Transaction',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach(groupTransactionElements(getVal($activityDataList, ['transaction'], [])) as $key => $groupedTransactions)
            <div class="activity-element-list">
                <div class="activity-element-label col-md-4">{{$key}}</div>
                <div class="activity-element-info">
                    @foreach($groupedTransactions as $transaction)
                        <li>{!! getCurrencyValueDate(getVal($transaction, ['value', 0]) , "transaction") !!}
                        </li>
                        <div class="groupingBtn">
                 <a class="edit" href="{{route('activity.transaction.edit',[$id,$transaction['id']])}}">hi</a> 
                       <form method="POST" id="test-form" action="{{ route('activity.transaction.destroy', [$id, $transaction['id']] )}}?main=1">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                            <input  type="submit" id="testBtn">
                        </form>      
                        </div>
                        <div class="toggle-btn">
                            <span class="show-more-info">@lang('global.show_more_info')</span>
                            <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.transaction_reference')</div>
                                <div class="activity-element-info">{!! checkIfEmpty(getVal($transaction, ['reference'])) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.description')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative(getVal($transaction, ['description', 0], [])) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transaction, ['description', 0, 'narrative'], []))])
                                </div>
                            </div>
                            @if(session('version') != 'V201')
                                @if(array_key_exists('humanitarian' , $transaction))
                                    <div class="element-info">
                                        <div class="activity-element-label">@lang('elementForm.humanitarian')</div>
                                        @if($transaction['humanitarian'] == "")
                                            <div class="activity-element-info"><em>@lang('global.not_available')</em></div>
                                        @elseif($transaction['humanitarian'] == 1)
                                            <div class="activity-element-info">@lang('elementForm.yes')</div>
                                        @elseif($transaction['humanitarian'] == 0)
                                            <div class="activity-element-info">@lang('elementForm.no')</div>
                                        @endif
                                    </div>
                                @endif
                            @endif
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.transaction_type')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('TransactionType' , getVal($transaction, ['transaction_type', 0, 'transaction_type_code']) , -4) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.transaction_date')</div>
                                <div class="activity-element-info">{{ formatDate(getVal($transaction, ['transaction_date', 0, 'date'])) }}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.provider_organisation')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative(getVal($transaction, ['provider_organization', 0], []), trans('global.no_name_available')) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transaction, ['provider_organization', 0, 'narrative'], []))])
                                    {!! getTransactionProviderDetails(getVal($transaction, ['provider_organization', 0], []), 'provider') !!}
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.receiver_organisation')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative(getVal($transaction, ['receiver_organization', 0], []), trans('global.no_name_available')) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transaction, ['receiver_organization', 0, 'narrative'], []))])
                                    {!! getTransactionProviderDetails($transaction['receiver_organization'][0] , 'receiver') !!}
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.disbursement_channel')</div>
                                <div class="activity-element-info">{!! checkIfEmpty($getCode->getCodeNameOnly('DisbursementChannel' , getVal($transaction, ['disbursement_channel',0,'disbursement_channel_code']))) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.sector')</div>
                                <div class="activity-element-info">
                                    {!! getSectorInformation(getVal($transaction,['sector',0],[] ), "") !!}
                                    {!! getTransactionSectorDetails(getVal($transaction,['sector',0],[])) !!} <br>
                                    {!! getFirstNarrative(getVal($transaction,['sector',0],[])) !!}
                                </div>
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transaction,['sector',0,'narrative'],[]))])
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.recipient_country')</div>
                                <div class="activity-element-info">
                                    {!! getCountryNameWithCode(getVal($transaction, ['recipient_country',0,'country_code'])) !!}
                                    <br>
                                    @if(!empty($transaction['recipient_country'][0]['narrative'][0]['narrative']))
                                        {!! getFirstNarrative(getVal($transaction, ['recipient_country', 0], [])) !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transaction, ['recipient_country', 0, 'narrative'], []))])
                                    @endif
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.recipient_region')</div>
                                <div class="activity-element-info">
                                    {!! getCodeNameWithCodeValue('Region' , getVal($transaction,['recipient_region',0,'region_code']) , -5) !!}
                                    <br>
                                    {!! getRecipientRegionDetails(getVal($transaction, ['recipient_region', 0],[])) !!} <br> <br>
                                    @if(!empty(getVal($transaction, ['recipient_region', 0, 'narrative', 0, 'narrative'])))
                                        {!! getFirstNarrative(getVal($transaction, ['recipient_region', 0])) !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($transaction, ['recipient_region', 0, 'narrative'], []))])
                                    @endif
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.flow_type')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('FlowType' , getVal($transaction, ['flow_type',0,'flow_type']) , -4) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.finance_type')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('FinanceType' , getVal($transaction, ['finance_type',0,'finance_type']) , -5) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.aid_type')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('AidType' , getVal($transaction,['aid_type',0,'aid_type'] ), -5) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.tied_status_code')</div>
                                <div class="activity-element-info">{!! getCodeNameWithCodeValue('TiedStatus' , getVal($transaction, ['tied_status', 0, 'tied_status_code']) , -4) !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.transaction.index', $id)}}" class="view" style="position:absolute;top:0;right:20px;">@lang('global.edit')</a>
    </div>
@endif
