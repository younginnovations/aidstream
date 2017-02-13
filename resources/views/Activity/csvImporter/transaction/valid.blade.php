<div class="panel panel-default">
    @if ($transactions)
        @foreach ($transactions as $index => $transaction)
            <div class="panel-heading">
                <label>
                    <span class="panel-content-heading">
                        <h3>
                            <input type="checkbox" value="{{ $index }}" name="transactions[]" checked/>
                            <span></span>
                            <span class="panel-title">
                                @if(($reference = getVal($transaction, ['transaction',0, 'reference'], '')) != "")
                                    {{$reference.' -'}}
                                @endif
                                @if(($amount = getVal($transaction,['transaction', 0, 'value',0,'amount'])) != "")
                                    {{$amount}}
                                @endif
                                @if(($currency = getVal($transaction,['transaction', 0, 'value',0,'currency'])) != "")
                                    {{$currency.' -'}}
                                @endif
                                @if(($date = getVal($transaction,['transaction', 0, 'transaction_date', 0, 'date'])) != "")
                                    {{$date.' -'}}
                                @endif
                                @if(($type = getVal($transaction, ['transaction', 0,'transaction_type', 0, 'transaction_type_code'])) != "")
                                    {{$type}}
                                @endif

                                @if(getVal($transaction,['existed']) == true)
                                    <span class="badge">@lang('global.existing')</span>
                                @else
                                    <span class="badge">@lang('global.new')</span>
                                @endif
                            </span>
                        </h3>
                    </span>
                </label>
            </div>
        @endforeach
    @endif
</div>
