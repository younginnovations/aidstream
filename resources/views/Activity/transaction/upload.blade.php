@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <a href="{{ route('activity.transaction.index', $id) }}" class="btn btn-primary">Back to Transaction List</a>
                @if(count($errors)>0)
                    @foreach($errors->all() as $error)
                        <ul>
                        <li style="color:red;">{{$error}}</li>
                        </ul>
                    @endforeach
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">Activity Transaction</div>
                    <div class="panel-body">
                        <h3>{{ $activity->IdentifierTitle }}</h3>
                        {!! form($form) !!}
                    </div>
                </div>
                <a href="/download-detailed-transaction" class="btn btn-primary">Download Detailed Transaction Template</a>
                <div>Contains all information about transaction. Ideal if you download your existing transaction from Download My Data page and want to update the transactions in bulk. Manual filling can be difficult as you have to ensure you use proper code values while filling certain fields. The first three fields (Activity_Identifier, Activity_Title, Default_currency) are ignored during uploading. This is done to make it consistent with transaction download via Download My Data.</div>
            </div>
            @include('includes.activity.element_menu')
        </div>
    </div>
@stop
