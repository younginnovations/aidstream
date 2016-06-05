<div class="hidden" id="transaction-form-clone">
    {!! Form::hidden('transaction[index][transaction_type][0][transaction_type_code]', $transactionType) !!}
            <div class="col-sm-6">
                {!! Form::label('transaction[index][reference]', 'Transaction Reference', ['class' => 'control-label required']) !!}
                {!! Form::text('transaction[index][reference]', null, ['class' => 'form-control', 'required' => 'required']) !!}
            </div>

            <div class="col-sm-6">
                {!! Form::label('transaction[index][transaction_date][0][date]', 'Transaction Date', ['class' => 'control-label required']) !!}
                {!! Form::text('transaction[index][transaction_date][0][date]', null, ['class' => 'form-control added-datepicker', 'required' => 'required']) !!}
            </div>

            <div class="col-sm-6">
                {!! Form::label('transaction[index][value][0][amount]', 'Amount', ['class' => 'control-label required']) !!}
                {!! Form::text('transaction[index][value][0][amount]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! Form::text('transaction[index][value][0][date]', null, ['class' => 'hidden']) !!}
            </div>

            <div class="col-sm-6">
                {!! Form::label('transaction[index][value][0][currency]', 'Currency', ['class' => 'control-label required']) !!}
                {!! Form::select('transaction[index][value][0][currency]', ['' => 'Select one of the following.'] + $currency, $defaultCurrency, ['class' => 'form-control', 'required' => 'required']) !!}
            </div>

            <div class="col-sm-6">
                {!! Form::label('transaction[index][description][0][narrative][0][narrative]', 'Description', ['class' => 'control-label']) !!}
                {!! Form::text('transaction[index][description][0][narrative][0][narrative]', null, ['class' => 'form-control']) !!}
                {!! Form::hidden('transaction[index][description][0][narrative][0][language]', null) !!}
            </div>

            <div class="col-sm-6">
                @if($transactionType == 1)
                    {!! Form::label('transaction[index][provider_organization][0][narrative][0][narrative]', 'Provider Organization', ['class' => 'control-label']) !!}
                @else
                    {!! Form::label('transaction[index][provider_organization][0][narrative][0][narrative]', 'Receiver Organization', ['class' => 'control-label']) !!}
                @endif
                {!! Form::text('transaction[index][provider_organization][0][narrative][0][narrative]', null, ['class' => 'form-control']) !!}
                {!! Form::text('transaction[index][provider_organization][0][narrative][0][language]', null, ['class' => 'hidden']) !!}
            </div>

    <a href="javascript:void(0)" onclick="removeBlock(this)" class="remove_from_collection">Remove</a>
</div>
