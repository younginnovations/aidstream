@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h3>{{ $activity->IdentifierTitle }}</h3>
                <strong>Transactions</strong>
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
                    @forelse($activity->getTransactions() as  $transaction)
                        <tr>
                            <td>{{ $transaction['reference'] }}</td>
                            <td>{{ $transaction['transaction_type'][0]['transaction_type_code'] }}</td>
                            <td>{{ $transaction['value'][0]['amount'] }}</td>
                            <td>{{ $transaction['value'][0]['date'] }}</td>
                            <td>
                                <a class="btn btn-small btn-primary" href="{{ route('activity.transaction.show', [$activity->id, $transaction['id']]) }}">Detail</a>
                                <a class="btn btn-small btn-success" href="{{ route('activity.transaction.edit', [$activity->id, $transaction['id']]) }}">Edit</a>
                                {!! Form::open(array('class' => 'pull-right','route' => array('activity.transaction.destroy',$activity->id, $transaction['id']))) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::submit('Delete', array('class' => 'btn btn-warning')) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td align="center" colspan="5">
                                No Transactions::
                            </td>
                        </tr>

                    @endforelse
                    </tbody>
                </table>
                <a href="{{ route('activity.transaction.create', $id) }}" class="btn btn-primary">Add New Transaction</a>
                <a href="{{ route('activity.transaction-upload.index', $id) }}" class="btn btn-primary">Upload Transaction</a>
            </div>
            <div class="col-xs-4">
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@stop
