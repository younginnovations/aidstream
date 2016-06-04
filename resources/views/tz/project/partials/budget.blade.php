@if (isset($edit))
    <div class="col-sm-12">
        <div class="col-sm-6">
            {!! Form::hidden('budget[0][budget_type]', 1, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::label('budget[0][period_start][0][date]', 'Budget Start Date', ['class' => 'control-label required']) !!}
            {!! Form::text('budget[0][period_start][0][date]', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
        </div>

        <div class="col-sm-6">
            {!! Form::label('budget[0][period_end][0][date]', 'Budget End Date', ['class' => 'control-label required']) !!}
            {!! Form::text('budget[0][period_end][0][date]', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
        </div>
    </div>

    <div class="col-sm-12">
        <div class="col-sm-6">
            {!! Form::label('budget[0][value][0][amount]', 'Amount', ['class' => 'control-label required']) !!}
            {!! Form::text('budget[0][value][0][amount]', null, ['class' => 'form-control', 'required' => 'required']) !!}
        </div>

        <div class="col-sm-6">
            {!! Form::label('budget[0][value][0][currency]', 'Currency', ['class' => 'control-label required']) !!}
            {!! Form::select('budget[0][value][0][currency]', ['' => 'Select one of the following.'] + $currency, null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::hidden('budget[0][value][0][value_date]', null, ['class' => 'form-control']) !!}
        </div>
    </div>
@else
    <div class="col-sm-12">
        <div class="col-sm-6">
            {!! Form::hidden('budget[0][budget_type]', 1, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::label('budget[0][period_start][0][date]', 'Budget Start Date', ['class' => 'control-label required']) !!}
            {!! Form::text('budget[0][period_start][0][date]', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
        </div>

        <div class="col-sm-6">
            {!! Form::label('budget[0][period_end][0][date]', 'Budget End Date', ['class' => 'control-label required']) !!}
            {!! Form::text('budget[0][period_end][0][date]', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
        </div>
    </div>

    <div class="col-sm-12">
        <div class="col-sm-6">
            {!! Form::label('budget[0][value][0][amount]', 'Amount', ['class' => 'control-label required']) !!}
            {!! Form::text('budget[0][value][0][amount]', null, ['class' => 'form-control', 'required' => 'required']) !!}
        </div>

        <div class="col-sm-6">
            {!! Form::label('budget[0][value][0][currency]', 'Currency', ['class' => 'control-label required']) !!}
            {!! Form::select('budget[0][value][0][currency]', ['' => 'Select one of the following.'] + $currency, getVal($settings, ['default_field_values', 0, 'default_currency']), ['class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::hidden('budget[0][value][0][value_date]', null, ['class' => 'form-control']) !!}
        </div>
    </div>
@endif
