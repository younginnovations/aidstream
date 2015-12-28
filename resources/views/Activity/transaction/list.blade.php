@extends('app')
@section('content')
    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="panel-content-heading panel-title-heading">Transactions of <span>{{$activity->IdentifierTitle}}</span></div>
                <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">
                @if(count($activity->getTransactions()) > 0)
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Internal Ref</th>
                        <th>Humanitarian</th>
                        <th>Transaction Type</th>
                        <th>Transaction Value</th>
                        <th>Transaction Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activity->getTransactions() as  $transaction)
                        <tr>
                            <td>{{ $transaction['reference'] }}</td>
                            <td>{{ $transaction['humanitarian'] == 0 ? 'False' : 'True' }}</td>
                            <td>{{ $transaction['transaction_type'][0]['transaction_type_code'] }}</td>
                            <td>{{ $transaction['value'][0]['amount'] }}</td>
                            <td>{{ $transaction['value'][0]['date'] }}</td>
                            <td>
                                <a class="view" href="{{ route('activity.transaction.show', [$activity->id, $transaction['id']]) }}">View</a>
                                <a class="edit" href="{{ route('activity.transaction.edit', [$activity->id, $transaction['id']]) }}">Edit</a>
                                <a class="delete" href="{{ url(sprintf('activity/%s/transaction/%s/delete', $activity->id, $transaction['id'])) }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-center no-data">No Transactions Created Yet::</div>
                @endif
                <a href="{{ route('activity.transaction.create', $id) }}" class="add">Add New Transaction</a>
                <a href="{{ route('activity.transaction-upload.index', $id) }}" class="upload">Upload Transaction</a>
            </div>
            @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@stop
