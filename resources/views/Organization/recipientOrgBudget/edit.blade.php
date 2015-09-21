@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Recipient Organization Budget</div>
                    <div class="panel-body">
                        {!! form_start($form) !!}
                        <div class="collection-container"
                             data-prototype="{{ form_row($form->recipientOrganizationBudget->prototype()) }}">
                            {!! form_row($form->recipientOrganizationBudget) !!}
                        </div>
                        <button type="button" class="add-to-collection">Add More Recipient Organization Budget</button>
                        {!! form_end($form) !!}
                    </div>
                </div>
            </div>

            <div class="col-xs-4">
                @include('includes.menu_org')
            </div>
        </div>
    </div>
@endsection