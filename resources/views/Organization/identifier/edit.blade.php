@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Organization Identifier</div>
                    <div class="panel-body">
                        {!! form_row($form->reporting_org->getChildren()[0]->reporting_organization_identifier) !!}
                    </div>
                </div>
            </div>

            <div class="col-xs-4">
                @include('includes.menu_org')
            </div>
        </div>
    </div>
@endsection
