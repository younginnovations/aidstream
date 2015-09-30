@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Iati Identifier</div>
                    <div class="panel-body">
                        <div class="hidden"
                             id="reporting_organization_identifier">{{ $reportingOrganization[0]['reporting_organization_identifier']  }}</div>
                        {!! form_start($form) !!}
                        <div class="panel panel-default">
                            <div class="panel-heading">IATI Identifier</div>

                            <div class="panel-body">
                                {!! form_rest($form) !!}
                            </div>
                        </div>
                        {!! form_end($form) !!}
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection
