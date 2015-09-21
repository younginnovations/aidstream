@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Reporting Organization</div>
                    <div class="panel-body">
                        {!! form_start($form) !!}
                        {!! form_row($form->reporting_organization_identifier) !!}
                        {!! form_row($form->reporting_organization_type) !!}
                        <div class="collection-container"
                             data-prototype="{{ form_row($form->narrative->prototype()) }}">
                            {!! form_row($form->narrative) !!}
                        </div>
                        <button type="button" class="add-to-collection">Add More Reporting Organization</button>
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