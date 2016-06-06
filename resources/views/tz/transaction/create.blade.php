@extends('tz.base.sidebar')

@section('title', 'Create Transaction')
@inject('code', 'App\Helpers\GetCodeName')

@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                @if($transactionType == 1)
                    <div>Add Incoming Funds</div>
                @elseif($transactionType == 3)
                    <div>Add Disbursement</div>
                @elseif($transactionType == 4)
                    <div>Add Expenditure</div>
                @endif
            </div>
            <div class="panel-body">
                <div class="create-form create-activity-form create-project-form">
                    {!! Form::open(['route' => ['project.transaction.store', $id], 'method' => 'POST']) !!}
                    {!! Form::hidden('transaction[0][transaction_type][0][transaction_type_code]', $transactionType) !!}
                            <div class="col-sm-6">
                                {!! Form::label('transaction[0][reference]', 'Transaction Reference', ['class' => 'control-label required']) !!}
                               {!! Form::text('transaction[0][reference]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('transaction[0][transaction_date][0][date]', 'Transaction Date', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][transaction_date][0][date]', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('transaction[0][value][0][amount]', 'Amount', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][value][0][amount]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                {!! Form::text('transaction[0][value][0][date]', null, ['class' => 'hidden']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('transaction[0][value][0][currency]', 'Currency', ['class' => 'control-label required']) !!}
                                {!! Form::select('transaction[0][value][0][currency]', ['' => 'Select one of the following.'] + $currency, $defaultCurrency, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('transaction[0][description][0][narrative][0][narrative]', 'Description', ['class' => 'control-label']) !!}
                                {!! Form::text('transaction[0][description][0][narrative][0][narrative]', null, ['class' => 'form-control']) !!}
                                {!! Form::hidden('transaction[0][description][0][narrative][0][language]', null) !!}
                            </div>

                            <div class="col-sm-6">
                                @if($transactionType == 1)
                                    {!! Form::label('transaction[0][provider_organization][0][narrative][0][narrative]', 'Provider Organization', ['class' => 'control-label']) !!}
                                @else
                                    {!! Form::label('transaction[0][provider_organization][0][narrative][0][narrative]', 'Receiver Organization', ['class' => 'control-label']) !!}
                                @endif
                                {!! Form::text('transaction[0][provider_organization][0][narrative][0][narrative]', null, ['class' => 'form-control']) !!}
                                {!! Form::text('transaction[0][provider_organization][0][narrative][0][language]', null, ['class' => 'hidden']) !!}
                            </div>
                    {!! Form::submit('Save', ['class' => 'pull-left btn-form', 'id' => 'submit-transaction']) !!}

                    @if($transactionType == 1)
                        <button type="button" id="add-more-transaction" class="add-more">Add Another Incoming Funds</button>
                    @elseif($transactionType == 3)
                        <button type="button" id="add-more-transaction" class="add-more">Add More Disbursement</button>
                    @elseif($transactionType == 4)
                        <button type="button" id="add-more-transaction" class="add-more">Add Another Expenditure</button>
                    @endif

                    {!! Form::close() !!}

                    @include('tz.transaction.partials.transaction-add')
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ asset('/js/tz/transaction.js') }}"></script>
@stop
