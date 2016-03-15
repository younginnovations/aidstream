@extends('app')

@section('title', 'Upload Activity Transaction - ' . $activity->IdentifierTitle)

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="panel-content-heading panel-title-heading">Upload Transaction for <span>{{$activity->IdentifierTitle}}</span>
                    <a href="{{ route('activity.transaction.index', $id) }}" class="btn btn-primary pull-right back-to-transaction">Back to Transaction List</a>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                            <div class="download-transaction-wrap">
                                <a href="/download-simple-transaction" class="btn btn-primary btn-form btn-submit">Download
                                    Simple Transaction Template</a>
                                <div>Contains very less information. Ideal if you are filling the information by hand.
                                    It doesn't contain many transaction elements for simplicity.
                                </div>
                            </div>
                            <div class="download-transaction-wrap">
                                <a href="/download-detailed-transaction" class="btn btn-primary btn-form btn-submit">Download
                                    Detailed Transaction Template</a>
                                <div>Contains all information about transaction. Ideal if you download your existing
                                    transaction from Download My Data page and want to update the transactions in bulk.
                                    Manual filling can be difficult as you have to ensure you use proper code values
                                    while filling certain fields. The first three fields (Activity_Identifier,
                                    Activity_Title, Default_currency) are ignored during uploading. This is done to make
                                    it consistent with transaction download via Download My Data.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@stop
