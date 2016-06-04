<div class="hidden" id="budget-clone">
    <div class="col-sm-12">
        <div class="col-sm-6">
            {!! Form::hidden('budget[index][budget_type]', 1, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::label('budget[index][period_start][0][date]', 'Budget Start Date', ['class' => 'control-label required']) !!}
            {!! Form::text('budget[index][period_start][0][date]', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
        </div>

        <div class="col-sm-6">
            {!! Form::label('budget[index][period_end][0][date]', 'Budget End Date', ['class' => 'control-label required']) !!}
            {!! Form::text('budget[index][period_end][0][date]', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
        </div>
    </div>

    <div class="col-sm-12">
        <div class="col-sm-6">
            {!! Form::label('budget[index][value][0][amount]', 'Amount', ['class' => 'control-label required']) !!}
            {!! Form::text('budget[index][value][0][amount]', null, ['class' => 'form-control', 'required' => 'required']) !!}
        </div>

        <div class="col-sm-6">
            {!! Form::label('budget[index][value][0][currency]', 'Currency', ['class' => 'control-label required']) !!}
            {!! Form::select('budget[index][value][0][currency]', ['' => 'Select one of the following.'] + $currency, getVal($settings, ['default_field_values', 0, 'default_currency']), ['class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::hidden('budget[index][value][0][value_date]', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <a href="javascript:void(0)" onclick="removeBudget(this)" class="remove_from_collection">Remove</a>
</div>