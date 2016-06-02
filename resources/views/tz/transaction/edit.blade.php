@extends('tz.base.sidebar')

@section('title', 'Edit Transaction')
@inject('code', 'App\Helpers\GetCodeName')

@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                <div>Edit Transaction</div>
            </div>
            <div class="panel-body">
                <div class="col-sm-12 panel-transaction-heading">
                    @if($transactionType == 1)
                        Incoming Funds
                    @elseif($transactionType == 3)
                        Disbursements
                    @elseif($transactionType == 4)
                        Expenditure
                    @endif
                </div>
                <div class="create-form create-activity-form create-project-form">
                    {!! Form::open(['route' => ['transaction.update', $projectId, $transactionType], 'method' => 'POST']) !!}
                    {!! Form::hidden('activity_id', $projectId) !!}

                            @foreach($transactions as $key => $transaction)
                                {!! Form::hidden("transaction[$key][id]", $transactions[$key]['id']) !!}
                                {!! Form::hidden("transaction[$key][transaction_type][0][transaction_type_code]", $transactionType) !!}
                                <div class="col-sm-6">
                                    {!! Form::label('reference', 'Transaction Reference', ['class' => 'control-label required']) !!}
                                    {!! Form::text("transaction[$key][reference]", $transaction['transaction']['reference'], ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('transaction_date', 'Transaction Date', ['class' => 'control-label required']) !!}
                                    {!! Form::text("transaction[$key][transaction_date][0][date]", $transaction['transaction']['transaction_date'][0]['date'], ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('amount', 'Amount', ['class' => 'control-label required']) !!}
                                    {!! Form::text("transaction[$key][value][0][amount]", $transaction['transaction']['value'][0]['amount'], ['class' => 'form-control', 'required' => 'required']) !!}
                                    {!! Form::text("transaction[$key][value][0][date]", null, ['class' => 'hidden']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('currency', 'Currency', ['class' => 'control-label required']) !!}
                                    {!! Form::select("transaction[$key][value][0][currency]", ['' => 'Select one of the following.'] + $currency, $transaction['transaction']['value'][0]['currency'], ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('description', 'Description', ['class' => 'control-label required']) !!}
                                    {!! Form::text("transaction[$key][description][0][narrative][0][narrative]", $transaction['transaction']['description'][0]['narrative'][0]['narrative'], ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('provider_org', 'Receiver Organization', ['class' => 'control-label required']) !!}
                                    {!! Form::text("transaction[$key][provider_organization][0][narrative][0][narrative]", $transaction['transaction']['provider_organization'][0]['narrative'][0]['narrative'], ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                            @endforeach
                    {!! Form::submit('Save', ['class' => 'pull-left btn-form', 'id' => 'submit-transaction']) !!}

                    @if($transactionType == 1)
                        <button type="button" id="add-more-transaction-edit" class="add-more">Add More Incoming Funds</button>
                    @elseif($transactionType == 3)
                        <button type="button" id="add-more-transaction-edit" class="add-more">Add More Disbursements</button>
                    @elseif($transactionType == 4)
                        <button type="button" id="add-more-transaction-edit" class="add-more">Add More Expenditure</button>
                    @endif

                    {!! Form::close() !!}

                    @include('tz.transaction.partials.transaction-add')
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        var currentTransactionCount = "{{ count($transactions) - 1 }}";
    </script>
    <script src="{{ asset('/js/tz/transaction.js') }}"></script>
@stop
