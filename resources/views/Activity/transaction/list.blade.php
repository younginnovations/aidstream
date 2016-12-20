@extends('app')

@section('title', trans('title.transactions').' - ' . $activity->IdentifierTitle)

@section('content')
    @inject('code', 'App\Helpers\GetCodeName')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>@lang('element.transactions')</span>
                        <div class="element-panel-heading-info"><span>{{$activity->IdentifierTitle}}</span></div>
                        <div class="btn-action-wrap">
                            @if(count($activity->getTransactions()) > 0)
                                <ul class="add-dropdown">
                                    <li class="dropdown">
                                        <div><span class="btn btn-primary dropdown-toggle add-new-btn" data-toggle="dropdown">@lang('global.add_new_transaction')<span class="caret"></span></span></div>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="{{ route('activity.transaction.create', $id) }}" class="">@lang('global.add_new_transaction')</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('activity.transaction-upload.index', $id) }}" class="">@lang('global.upload_transaction')</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            @endif
                            <a href="{{ route('activity.show', $id) }}" class="btn btn-primary btn-view-it">@lang('global.view_activity')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper transaction-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @if(count($activity->getTransactions()) > 0)
                                <table class="table table-striped" id="data-table">
                                    <thead>
                                    <tr>
                                        <th>@lang('global.internal_ref')</th>
                                        <th>@lang('global.transaction_type')</th>
                                        <th>@lang('global.transaction_value')</th>
                                        <th class="default-sort">@lang('global.transaction_date')</th>
                                        <th class="no-sort">@lang('global.actions')</th>
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
                                                   href="{{ route('activity.transaction.edit', [$activity->id, $transaction['id']]) }}">@lang('global.edit')</a>
                                                <a class="delete"
                                                   href="{{ url(sprintf('activity/%s/transaction/%s/delete', $activity->id, $transaction['id'])) }}">@lang('global.delete')</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center no-data no-result-data">
                                    <p>@lang('global.not_added',['type' => 'transactions']).</p>
                                    <div class="no-data-btn">
                                        <a href="{{ route('activity.transaction.create', $id) }}"
                                           class="btn btn-primary">@lang('global.add_new_transaction')</a>
                                        <a href="{{ route('activity.transaction-upload.index', $id) }}"
                                           class="btn btn-primary btn-upload">@lang('global.upload_transaction')</a>
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
