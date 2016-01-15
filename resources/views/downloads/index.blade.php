@extends('app')

@section('title', 'Downloads')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                <div class="panel panel-default">
                    <div class="panel-content-heading panel-title-heading">Download Csv</div>
                    <div class="panel-body">
                       <div>
                            <a href="{{route('download.simple')}}" class="btn btn-primary">Simple</a>
                            <div>Contains full information for each activity with one row per activity. Multiple values for a column (e.g. multiple transactions, multiple sectors) are separated by ";".</div>
                       </div>
                       <hr>
                       <div>
                           <a href="{{ route('download.complete') }}" class="btn btn-primary">Complete</a>
                           <div>Contains a row for each aid activity. Each activity contains total figures for each type of transaction. Useful for quick verification of the data.</div>
                       </div>
                       <hr>
                        <div>
                            <a href="" class="btn btn-primary">Transactions</a>
                            <div>Contains a detail list of transactions for all activities with currencies, amounts and classifications. A row for each transaction.</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
