@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h3>{{ $activity->IdentifierTitle }}</h3>
                <strong>Transactions</strong>
                @if(count($activity->getTransactions()) > 0)
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <td>Internal Ref</td>
                        <td>Transaction Type</td>
                        <td>Transaction Value</td>
                        <td>Transaction Date</td>
                        <td>Actions</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activity->getTransactions() as  $transaction)
                        <tr>
                            <td>{{ $transaction['reference'] }}</td>
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
                <a href="{{ route('activity.transaction.create', $id) }}" class="btn btn-primary">Add New Transaction</a>
                <a href="{{ route('activity.transaction-upload.index', $id) }}" class="btn btn-primary">Upload Transaction</a>
            </div>
            @include('includes.activity.element_menu')
        </div>
    </div>
@stop
