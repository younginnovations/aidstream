<div class="panel panel-default">
    @if($transactions)
        @foreach ($transactions as $index => $transaction)
            <div class="panel-heading">
                <label>
                    <span class="panel-content-heading">
                        <h3>
                            <input type="checkbox" disabled="disabled" value="{{ $index }}"/>
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
                                {{$type . ' -'}}
                            @endif
                        </span>
                        </h3>
                    </span>
                    <ul class="data-listing">
                        @foreach (getVal($transaction, ['errors'], []) as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </label>
            </div>
        @endforeach
    @endif
</div>
