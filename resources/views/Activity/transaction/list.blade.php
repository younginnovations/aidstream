@extends('app')

@section('title', 'Activity Transactions - ' . $activity->IdentifierTitle)

@section('content')
    @inject('code', 'App\Helpers\GetCodeName')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>Transactions</span>
                        <div class="element-panel-heading-info"><span>{{$activity->IdentifierTitle}}</span></div>
                    @if(count($activity->getTransactions()) > 0)
                        <ul class="add-dropdown">
                            <li class="dropdown">
                                <div><span class="btn btn-primary dropdown-toggle add-new-btn" data-toggle="dropdown">Add New
                                    Transaction<span class="caret"></span></span></div>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('activity.transaction.create', $id) }}" class="">Add New
                                            Transaction</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('activity.transaction-upload.index', $id) }}" class="">Upload
                                            Transaction</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endif

                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper transaction-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @if(count($activity->getTransactions()) > 0)
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Internal Ref</th>
                                        <th>Transaction Type</th>
                                        <th>Transaction Value</th>
                                        <th>Transaction Date</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($activity->getTransactions() as  $transaction)
                                        <tr data-href="{{ route('activity.transaction.show', [$activity->id, $transaction['id']]) }}"
                                            class="clickable-row">
                                            <td>{{ $transaction['reference'] }}</td>
                                            <td>{{ $code->getActivityCodeName('TransactionType', $transaction['transaction_type'][0]['transaction_type_code'])}}</td>
                                            <td>{{ $transaction['value'][0]['amount'] }}</td>
                                            <td>{{ formatDate($transaction['transaction_date'][0]['date']) }}</td>
                                            <td>
                                                <a class="view" href="{{ route('activity.transaction.show', [$activity->id, $transaction['id']]) }}"></a>
                                                <a class="edit"
                                                   href="{{ route('activity.transaction.edit', [$activity->id, $transaction['id']]) }}">Edit</a>
                                                <a class="delete"
                                                   href="{{ url(sprintf('activity/%s/transaction/%s/delete', $activity->id, $transaction['id'])) }}">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center no-data no-result-data">
                                    You haven’t added any transactions yet.
                                    <div class="no-data-btn">
                                        <a href="{{ route('activity.transaction.create', $id) }}"
                                           class="btn btn-primary">Add New Transaction</a>
                                        <a href="{{ route('activity.transaction-upload.index', $id) }}"
                                           class="btn btn-primary upload">Upload Transaction</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@stop
